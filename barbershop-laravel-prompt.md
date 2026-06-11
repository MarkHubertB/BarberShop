# Laravel Premium Barbershop Website — Full Build Prompt

---

## PROJECT OVERVIEW

Build a complete **Laravel 11** premium men's barbershop website called **"The Blade Room"** (or substitute your own name). No customer login/registration. Guest booking only. Admin panel protected by a single admin account.

---

## TECH STACK (exact versions)

| Layer | Choice |
|---|---|
| Backend | Laravel 11 |
| Frontend | Blade + Alpine.js + Livewire 3 |
| Styling | Tailwind CSS v3 (CDN or Vite) |
| Database | MySQL |
| Auth | Laravel Breeze (admin only, `admin` guard) |
| Mail | Laravel Notification + Mailtrap (dev) |
| Scheduler | Laravel Task Scheduler (booking reminders) |

---

## DESIGN SYSTEM (enforce throughout all Blade files)

```
Colors:
  --bg-deep:     #0e0e0e   /* page background */
  --bg-card:     #161616   /* cards, panels */
  --bg-surface:  #1f1f1f   /* inputs, modals */
  --gold:        #c9a84c   /* primary accent */
  --gold-muted:  #8a6f32   /* hover/disabled gold */
  --text-primary:#f0ece4   /* headings */
  --text-muted:  #6b6b6b   /* labels, captions */
  --border:      #2a2a2a   /* dividers, input borders */
  --danger:      #b94a4a   /* errors, unavailable slots */

Typography:
  Display: "Playfair Display" (serif) — headings, hero, service names
  Body:    "Inter" (sans-serif) — paragraphs, labels, buttons
  Import both from Google Fonts in app.blade.php

Spacing scale: 4px base (use Tailwind spacing)
Border radius: 2px on cards, 0px on buttons (sharp), 4px on inputs
Transitions: 200ms ease on all hover states
```

---

## FILE STRUCTURE TO GENERATE

Generate every file listed below. Do not skip any.

```
app/
  Models/
    Service.php
    Barber.php
    Booking.php
    TimeSlot.php
  Http/
    Controllers/
      BookingController.php
      Admin/
        DashboardController.php
        BookingAdminController.php
        ServiceController.php
        BarberController.php
    Requests/
      StoreBookingRequest.php
    Livewire/
      BookingCalendar.php        ← Livewire component

database/
  migrations/
    create_services_table.php
    create_barbers_table.php
    create_bookings_table.php
    create_time_slots_table.php
  seeders/
    DatabaseSeeder.php
    ServiceSeeder.php
    BarberSeeder.php
    AdminSeeder.php

resources/views/
  layouts/
    app.blade.php               ← public layout (dark, Google Fonts, Alpine, Livewire)
    admin.blade.php             ← admin layout (sidebar nav)
  pages/
    home.blade.php              ← full landing page
    booking.blade.php           ← booking page (embeds Livewire calendar)
    confirmation.blade.php      ← post-booking confirmation
  livewire/
    booking-calendar.blade.php  ← calendar UI component
  admin/
    dashboard.blade.php
    bookings/
      index.blade.php
      show.blade.php
    services/
      index.blade.php
      create.blade.php
      edit.blade.php
    barbers/
      index.blade.php
      create.blade.php
      edit.blade.php

routes/
  web.php
  admin.php                     ← admin routes (auth:admin middleware)

app/Notifications/
  BookingConfirmed.php
  BookingReminder.php

app/Console/
  Commands/
    SendBookingReminders.php
```

---

## DATABASE SCHEMA

### `services`
```sql
id, name, slug, description, duration_minutes, price, image_url, is_active, timestamps
```

### `barbers`
```sql
id, name, bio, photo_url, specialty, is_active, timestamps
```

### `time_slots`
```sql
id, label (e.g. "9:00 AM"), start_time (TIME), end_time (TIME), is_active, timestamps
```
Seed 12 slots: 9:00 AM to 8:00 PM, 1-hour intervals.

### `bookings`
```sql
id, reference_code (unique, 8-char uppercase e.g. BLD-A3X9),
customer_name, customer_email, customer_phone,
service_id (FK), barber_id (FK nullable),
booking_date (DATE), time_slot_id (FK),
notes, status ENUM(pending, confirmed, completed, cancelled),
reminder_sent (boolean, default false),
timestamps
```

**Unique constraint:** `[barber_id, booking_date, time_slot_id]` — prevents double booking per barber per slot.

---

## MODELS & RELATIONSHIPS

**Service.php**
- `hasMany(Booking::class)`
- scope: `scopeActive($query)`

**Barber.php**
- `hasMany(Booking::class)`
- method: `isAvailableOn($date, $timeSlotId): bool` — queries bookings table

**Booking.php**
- `belongsTo(Service::class)`
- `belongsTo(Barber::class)`
- `belongsTo(TimeSlot::class)`
- boot: auto-generate `reference_code` on creating

**TimeSlot.php**
- `hasMany(Booking::class)`

---

## LIVEWIRE COMPONENT: `BookingCalendar`

This is the most important component. Build it exactly as described.

### State properties
```php
public string $currentMonth;    // "2026-06"
public ?string $selectedDate = null;
public ?int $selectedSlot = null;
public ?int $selectedBarber = null;
public ?int $selectedService = null;
public array $bookedSlots = []; // slot IDs booked on selectedDate
public string $customerName = '';
public string $customerEmail = '';
public string $customerPhone = '';
public string $notes = '';
public string $step = 'calendar'; // 'calendar' | 'slots' | 'form' | 'done'
```

### Methods
```php
// Navigate months (disable past months)
public function prevMonth(): void
public function nextMonth(): void

// Called when user clicks a date cell
public function selectDate(string $date): void
  // sets $selectedDate
  // queries bookings for that date grouped by time_slot_id
  // builds $bookedSlots array (slot IDs that have NO available barber)
  // sets step = 'slots'

// Called when user clicks a time slot
public function selectSlot(int $slotId): void
  // only proceed if slot not in $bookedSlots
  // sets $selectedSlot, step = 'form'

// Final submit
public function submitBooking(): void
  // validate fields
  // re-check slot availability (race condition guard)
  // create Booking record
  // dispatch BookingConfirmed notification
  // step = 'done'
```

### Availability logic (critical)
A time slot on a given date is **UNAVAILABLE** if:
- All active barbers have a confirmed/pending booking on that `[date, slot]`
- OR if no specific barber selected: ALL barbers are booked for that slot

A **date is fully booked** (shown differently on calendar) if ALL slots on that date are unavailable.

---

## BOOKING CALENDAR UI (`livewire/booking-calendar.blade.php`)

### Step 1 — Calendar view
```
┌─────────────────────────────────────────┐
│  ← June 2026 →                          │
│  Mo  Tu  We  Th  Fr  Sa  Su             │
│   1   2   3   4   5   6   7             │
│   8   9  10  11  12  13  14             │
│  15  16  17  18  19  20  21             │
│  22  23  24  25  26  27  28             │
│  29  30                                 │
└─────────────────────────────────────────┘
```
- Past dates: `opacity-30 cursor-not-allowed`
- Today: gold border ring
- Selected date: gold background, dark text
- Fully booked date: dark red tint + strikethrough text
- Available dates: hover gold border

### Step 2 — Time slot picker (shown below calendar after date selected)
```
┌─────────────────────────────────────────┐
│  Available times for June 9             │
│                                         │
│  [9:00 AM]  [10:00 AM]  [11:00 AM]     │
│  [12:00 PM] [1:00 PM ✗] [2:00 PM]     │
│  [3:00 PM]  [4:00 PM ✗] [5:00 PM]     │
└─────────────────────────────────────────┘
```
- Available slot: border-gold, hover fill gold
- Booked/unavailable: `bg-[#1a0a0a] border-[#b94a4a] text-[#6b6b6b] line-through cursor-not-allowed`
- Selected slot: `bg-[#c9a84c] text-[#0e0e0e] font-bold`

### Step 3 — Guest form (slides in after slot selected)
Fields: Full Name, Email, Phone, Select Service (dropdown), Preferred Barber (optional dropdown), Notes (textarea). Submit button: "Confirm Booking" — sharp gold button.

### Step 4 — Confirmation (same page, step = 'done')
Show: reference code (large, styled), service, date, time, barber. "Add to Calendar" link (Google Calendar URL). "Book Another" button resets state.

Animate between steps with Alpine.js `x-transition`.

---

## ROUTES (`routes/web.php`)

```php
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/book', [BookingController::class, 'index'])->name('booking');
Route::get('/confirmation/{reference}', [BookingController::class, 'confirmation'])->name('booking.confirmation');

// Admin auth
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Admin protected (middleware: auth:admin)
Route::prefix('admin')->middleware('auth:admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('bookings', BookingAdminController::class)->only(['index','show','update']);
    Route::resource('services', ServiceController::class);
    Route::resource('barbers', BarberController::class);
    Route::patch('bookings/{booking}/status', [BookingAdminController::class, 'updateStatus'])->name('bookings.status');
});
```

---

## ADMIN PANEL PAGES

### Layout (`admin.blade.php`)
Dark sidebar, gold logo top-left. Nav links: Dashboard, Bookings, Services, Barbers. Logout button bottom.

### Dashboard (`admin/dashboard.blade.php`)
4 stat cards: Total Bookings Today, Pending, Confirmed, Revenue This Month.
Recent bookings table (last 10): Reference | Customer | Service | Date & Time | Barber | Status badge | Actions.

### Bookings Index (`admin/bookings/index.blade.php`)
Full table with filters: date picker, status dropdown, search by name/reference.
Each row: Reference, Customer Name, Phone, Service, Date, Time, Barber, Status (colored badge), [View] [Confirm] [Cancel] action buttons.
Status badge colors: pending=amber, confirmed=gold, completed=green, cancelled=red.

### Booking Show (`admin/bookings/show.blade.php`)
Full booking details card. Status update dropdown + "Update" button. Notes field.

### Services CRUD
Index: table with name, price, duration, active toggle.
Create/Edit: form with all fields + image URL preview.

### Barbers CRUD
Index: card grid with photo, name, specialty, active toggle.
Create/Edit: form with all fields.

---

## SEEDERS

### `ServiceSeeder.php` — seed exactly these 10:

```php
[
  ['name'=>'Textured Crop',         'duration_minutes'=>30, 'price'=>250],
  ['name'=>'Textured Quiff',        'duration_minutes'=>35, 'price'=>280],
  ['name'=>'Modern Mullet',         'duration_minutes'=>40, 'price'=>300],
  ['name'=>'Blowout Low Taper Fade','duration_minutes'=>40, 'price'=>320],
  ['name'=>'Warrior Cut',           'duration_minutes'=>45, 'price'=>350],
  ['name'=>'Overgrown Buzz Cut',    'duration_minutes'=>25, 'price'=>200],
  ['name'=>'Messy Textured Crop',   'duration_minutes'=>35, 'price'=>270],
  ['name'=>'Slick Back Undercut',   'duration_minutes'=>40, 'price'=>300],
  ['name'=>'Curly Flow',            'duration_minutes'=>45, 'price'=>330],
  ['name'=>'Skin Fade',             'duration_minutes'=>30, 'price'=>280],
]
```

### `BarberSeeder.php` — seed 3 barbers: Marco, Diego, Rex

### `AdminSeeder.php`
```php
// Create admin user: email=admin@bladeroom.com, password=BladeRoom2026!
// Store in `users` table with role='admin' or use separate admins table
```

### `TimeSlotSeeder.php`
```php
// 9:00 AM to 8:00 PM, hourly = 12 slots
```

---

## NOTIFICATIONS

### `BookingConfirmed.php`
- Channel: mail
- Subject: "Your Booking is Confirmed — [Reference]"
- Body: reference code, service, date/time, barber name, shop address

### `BookingReminder.php`
- Channel: mail
- Subject: "See you tomorrow — [Reference]"
- Sent 24 hours before booking

### `SendBookingReminders.php` (Artisan Command)
```php
// Query bookings where booking_date = tomorrow AND reminder_sent = false AND status != cancelled
// Send BookingReminder notification, mark reminder_sent = true
```
Register in `routes/console.php`:
```php
Schedule::command('bookings:send-reminders')->dailyAt('10:00');
```

---

## HOME PAGE (`pages/home.blade.php`) — SECTIONS IN ORDER

1. **Hero** — Full viewport, dark bg, subtle noise texture overlay. Headline: *"Precision. Style. Confidence."* in Playfair Display 72px. Subtext: *"Premium cuts for the modern gentleman."* CTA: `<a href="/book">` gold button "Book Your Cut →"

2. **Services** — Section title "The Cuts". Grid: 2 cols desktop, 1 col mobile. Each card: service name (Playfair), price (gold), duration (muted), short description. Hover: gold left border appears.

3. **How It Works** — 3 columns: Choose Your Cut / Pick Date & Time / Show Up Fresh. Simple icon (SVG scissor, calendar, checkmark), bold label, one-line description.

4. **Our Barbers** — Card row with photo, name, specialty tag.

5. **Testimonials** — 3 quote cards. Dark bg, gold quote mark, italic text, client name.

6. **Footer** — Logo, address, hours (Mon–Sat 9AM–8PM), social icons, Google Maps embed.

---

## CRITICAL IMPLEMENTATION NOTES

1. **No customer auth** — zero login/register routes for public users.
2. **Slot unavailability is real-time** — Livewire re-fetches `$bookedSlots` every time `$selectedDate` changes.
3. **Race condition guard** — re-check availability inside `submitBooking()` before inserting, return error if slot was taken.
4. **Reference code** — auto-generated in `Booking::boot()` as `"BLD-" . strtoupper(Str::random(4))`, unique enforced.
5. **Past date protection** — both frontend (disabled cells) and backend (StoreBookingRequest validates `booking_date >= today()`).
6. **Admin guard** — use a separate `admin` guard in `config/auth.php` or simply use `role` column on users table with middleware check.
7. **Tailwind** — use CDN (`<script src="https://cdn.tailwindcss.com">`) with config block for custom colors if not using Vite.
8. **Images** — use `https://placehold.co/400x500/161616/c9a84c?text=Marco` style placeholders for barber photos.

---

## WHAT TO BUILD FIRST (order)

1. Migrations + Seeders (run `php artisan migrate --seed`)
2. Models + relationships
3. `TimeSlot` seeder (12 slots)
4. Livewire `BookingCalendar` component (PHP class + Blade)
5. `booking.blade.php` page embedding the Livewire component
6. `home.blade.php` landing page
7. `confirmation.blade.php`
8. Admin auth (login page + middleware)
9. Admin dashboard + bookings CRUD
10. Admin services + barbers CRUD
11. Notifications + scheduler
12. Final styling pass — ensure design tokens consistent across all views

---

*End of prompt. Every file listed above must be generated with full working code.*
