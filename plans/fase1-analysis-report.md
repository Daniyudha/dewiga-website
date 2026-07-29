# FASE 1 - Analisis Sistem

## 1. Versi
- Laravel 10.1.5
- PHP 8.1.34

## 2. Struktur Route Admin
- Prefix: `/admin` with `is_admin` + `auth` middleware
- Route names prefixed with `admin.`
- Multilingual support via `LaravelLocalization` for frontend only

## 3. Middleware Autentikasi
- `auth` (Laravel built-in)
- Custom `is_admin` middleware

## 4. Role & Permission
- No Spatie or package-based permission system
- Simple `is_admin` middleware check
- No granular permissions exist yet

## 5. Model & Migration Summary

### Schedules
- `schedules` table: id, travel_package_id, type(enum: open_trip/confirmed/pending), start_date, end_date, visitor_name, quota, booked, is_active
- **ISSUE**: `type` conflates data source AND status
- No source tracking (source_type/source_id or polymorphic)
- Schedule is created either: manually, from Booking auto-creation, or via admin form

### Bookings
- `bookings` table: id, name, email, number_phone, institution, date, start_date, end_date, travel_package_id, schedule_id, status(pending/confirmed/cancelled), notes, amount, people_count
- Has `BookingParticipant` children
- Relasi: belongsTo TravelPackage, belongsTo Schedule, hasMany participants

### Price Estimations
- `price_estimations` table: estimation_number(unique), institution_name, contact_person, whatsapp, arrival_date, departure_date, student_count, companion_count, service_participant_count, activity_participant_count, subtotal, actual_price_per_person, rounding_type, rounded_price_per_person, quotation_total, difference_amount, notes, created_by
- Has `PriceEstimationItem` children
- **ISSUE**: Completely isolated from Schedules - no integration

### Open Trip
- `open_trip_registrations`: linked to schedules via schedule_id
- `open_trip_participants`: linked to registrations
- Open trip schedules have type='open_trip'

### Activities
- `activities` table exists with bilingual content support

### Booking Process (from public form)
- `BookingController@store` (public) → creates Booking record
- BookingController also auto-creates Schedule if travel_package_id present
- Schedule type set to 'pending' or 'confirmed' based on booking status

## 6. Issues Found

### Critical:
1. **Schedule `type` conflates source and status**: type='confirmed' means it's both confirmed AND possibly a group booking, type='open_trip' is a source marker. This is ambiguous.
2. **No source tracking**: Can't tell if a schedule came from price estimation, public booking, or manual entry
3. **Price Estimation → Schedule integration missing**: Kalkulator Harga has no path to become a Schedule
4. **No payment tracking**: No payment records linked to schedules
5. **No status history/logging**

### Moderate:
6. Statuses are hardcoded strings throughout the codebase
7. Dashboard only shows website analytics
8. No rundown template system
9. No operational requirements calculation

## 7. Files to Create (FASE 2)
1. `database/migrations/XXXX_XX_XX_XXXXXX_add_source_to_schedules_table.php`
2. `app/Enums/ScheduleStatus.php`
3. `app/Enums/ScheduleSourceType.php`
4. `app/Services/PriceEstimationConversionService.php`
5. `app/Http/Controllers/Admin/ScheduleDetailController.php` (optional, or extend ScheduleController)
6. `resources/views/admin/price-calculator/partials/conversion-modal.blade.php`

## 8. Files to Modify (FASE 2)
1. `app/Models/Schedule.php` - add source_type/source_id, priceEstimation relation, new scopes
2. `app/Models/PriceEstimation.php` - add schedule relation, converted status
3. `app/Http/Controllers/Admin/PriceCalculatorController.php` - add convertToSchedule method
4. `resources/views/admin/price-calculator/show.blade.php` - add "Jadikan Schedule" button + status
5. `app/Http/Controllers/Admin/ScheduleController.php` - handle source on create
6. `app/Http/Requests/Admin/ScheduleRequest.php` - add source validation
7. `routes/web.php` - add convert route

## 9. Migration Plan (FASE 2)
```sql
ALTER TABLE schedules ADD COLUMN source_type VARCHAR(50) NULL;
ALTER TABLE schedules ADD COLUMN source_id BIGINT UNSIGNED NULL;
ALTER TABLE schedules ADD COLUMN price_estimation_id BIGINT UNSIGNED NULL;
ALTER TABLE schedules ADD INDEX schedules_source_index (source_type, source_id);
```

## 10. Implementation Strategy
- Use `source_type` (string: 'manual', 'public_booking', 'price_estimation', 'open_trip') + `source_id`
- NOT polymorphic - simpler, more compatible with existing code
- Add nullable `price_estimation_id` for direct FK to price_estimations
- Use single Schedule model with new attributes
- Use Service class for conversion logic to keep controllers clean