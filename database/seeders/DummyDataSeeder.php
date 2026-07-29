<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\OpenTripParticipant;
use App\Models\OpenTripRegistration;
use App\Models\PriceEstimation;
use App\Models\Schedule;
use App\Models\SchedulePayment;
use App\Models\ScheduleStatusHistory;
use App\Models\TravelPackage;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $tp = TravelPackage::first() ?? TravelPackage::create([
            'type' => 'Paket Wisata Edukasi', 'slug' => 'paket-edukasi',
            'location' => 'Desa Wisata Gabugan', 'duration' => '2 Hari 1 Malam',
            'description' => 'Paket edukasi budaya dan alam', 'price' => 150000, 'capacity' => 50,
        ]);

        // 1. Schedules (7 data)
        $s1 = Schedule::create(['travel_package_id' => $tp->id, 'type' => 'confirmed', 'status' => 'confirmed', 'source_type' => 'manual', 'visitor_name' => 'SMA N 1 Sleman - Studi Tour', 'start_date' => '2026-08-10', 'end_date' => '2026-08-11', 'quota' => 100, 'booked' => 89, 'is_active' => true]);
        $s2 = Schedule::create(['travel_package_id' => $tp->id, 'type' => 'pending', 'status' => 'pending', 'source_type' => 'public_booking', 'visitor_name' => 'SMK N 2 Yogyakarta', 'start_date' => '2026-08-20', 'end_date' => '2026-08-22', 'quota' => 50, 'booked' => 0, 'is_active' => true]);
        $s3 = Schedule::create(['travel_package_id' => $tp->id, 'type' => 'confirmed', 'status' => 'in_progress', 'source_type' => 'price_estimation', 'visitor_name' => 'SMA Cita Hati - Live In', 'start_date' => '2026-08-05', 'end_date' => '2026-08-08', 'quota' => 160, 'booked' => 156, 'is_active' => true]);
        $s4 = Schedule::create(['travel_package_id' => $tp->id, 'type' => 'confirmed', 'status' => 'completed', 'source_type' => 'manual', 'visitor_name' => 'SMP N 3 Bantul', 'start_date' => '2026-07-20', 'end_date' => '2026-07-21', 'quota' => 80, 'booked' => 76, 'is_active' => false]);
        $s5 = Schedule::create(['travel_package_id' => $tp->id, 'type' => 'open_trip', 'status' => 'pending', 'source_type' => 'open_trip', 'visitor_name' => 'Open Trip Gunung Kidul', 'start_date' => '2026-09-05', 'end_date' => '2026-09-06', 'quota' => 20, 'booked' => 5, 'is_active' => true]);
        $s6 = Schedule::create(['travel_package_id' => $tp->id, 'type' => 'pending', 'status' => 'cancelled', 'source_type' => 'manual', 'visitor_name' => 'Universitas Gadjah Mada', 'start_date' => '2026-07-15', 'end_date' => '2026-07-20', 'quota' => 30, 'booked' => 0, 'is_active' => false]);
        $s7 = Schedule::create(['travel_package_id' => $tp->id, 'type' => 'pending', 'status' => 'draft', 'visitor_name' => null, 'start_date' => '2026-09-01', 'end_date' => '2026-09-02', 'quota' => 25, 'booked' => 0, 'is_active' => true]);
        $this->command->info("Schedules: 7 created");

        // 2. Price Estimations
        $est1 = PriceEstimation::create(['estimation_number' => PriceEstimation::generateEstimationNumber(), 'institution_name' => 'SMA Cita Hati', 'contact_person' => 'Budi Santoso', 'whatsapp' => '08123456789', 'arrival_date' => '2026-09-07', 'departure_date' => '2026-09-10', 'student_count' => 146, 'companion_count' => 10, 'service_participant_count' => 156, 'activity_participant_count' => 146, 'subtotal' => 46800000, 'actual_price_per_person' => 300000, 'rounding_type' => 'up_5000', 'rounded_price_per_person' => 305000, 'quotation_total' => 47580000, 'difference_amount' => 780000, 'notes' => 'Kunjungan edukasi 4 hari 3 malam - SUDAH dikonversi', 'created_by' => 1]);
        $est2 = PriceEstimation::create(['estimation_number' => PriceEstimation::generateEstimationNumber(), 'institution_name' => 'SMAN 2 Yogyakarta', 'contact_person' => 'Siti Rahmawati', 'whatsapp' => '08765432100', 'arrival_date' => '2026-09-15', 'departure_date' => '2026-09-16', 'student_count' => 80, 'companion_count' => 8, 'service_participant_count' => 88, 'activity_participant_count' => 80, 'subtotal' => 26400000, 'actual_price_per_person' => 300000, 'rounding_type' => 'up_10000', 'rounded_price_per_person' => 310000, 'quotation_total' => 27280000, 'difference_amount' => 880000, 'notes' => 'Kunjungan 2 hari 1 malam - BELUM dikonversi', 'created_by' => 1]);
        Schedule::where('id', $s3->id)->update(['price_estimation_id' => $est1->id, 'source_id' => $est1->id]);
        $this->command->info("Estimations: 2 created (1 converted, 1 not)");

        // 3. Bookings
        Booking::create(['name' => 'Rina Wijaya', 'email' => 'rina@mail.com', 'number_phone' => '081111222333', 'institution' => 'SMK N 2 Yogya', 'date' => '2026-08-20', 'start_date' => '2026-08-20', 'end_date' => '2026-08-22', 'travel_package_id' => $tp->id, 'schedule_id' => $s2->id, 'status' => 'pending', 'people_count' => 45, 'notes' => '2 bus, tiba 08.00']);
        Booking::create(['name' => 'Ahmad Fauzi', 'email' => 'ahmad@mail.com', 'number_phone' => '082222333444', 'institution' => 'SMA N 3 Wates', 'date' => '2026-08-10', 'start_date' => '2026-08-10', 'end_date' => '2026-08-11', 'travel_package_id' => $tp->id, 'schedule_id' => $s1->id, 'status' => 'confirmed', 'people_count' => 89, 'notes' => 'Makan siang jam 12.00']);
        $this->command->info("Bookings: 2 created");

        // 4. Open Trip Registrations
        $reg1 = OpenTripRegistration::create(['name' => 'Andi Pratama', 'email' => 'andi@mail.com', 'number_phone' => '085555666777', 'date' => '2026-09-05', 'travel_package_id' => $tp->id, 'schedule_id' => $s5->id, 'status' => 'confirmed', 'people_count' => 3]);
        OpenTripParticipant::create(['open_trip_registration_id' => $reg1->id, 'name' => 'Andi Pratama', 'phone' => '085555666777']);
        OpenTripParticipant::create(['open_trip_registration_id' => $reg1->id, 'name' => 'Siti Aisyah', 'phone' => '085555666778']);
        OpenTripParticipant::create(['open_trip_registration_id' => $reg1->id, 'name' => 'Budi Hartono', 'phone' => '085555666779']);
        $reg2 = OpenTripRegistration::create(['name' => 'Dewi Sartika', 'email' => 'dewi@mail.com', 'number_phone' => '087777888999', 'date' => '2026-09-05', 'travel_package_id' => $tp->id, 'schedule_id' => $s5->id, 'status' => 'pending', 'people_count' => 2]);
        OpenTripParticipant::create(['open_trip_registration_id' => $reg2->id, 'name' => 'Dewi Sartika', 'phone' => '087777888999']);
        OpenTripParticipant::create(['open_trip_registration_id' => $reg2->id, 'name' => 'Rudi Hermawan', 'phone' => '087777888990']);
        $this->command->info("OpenTripRegs: 2 created (5 participants)");

        // 5. Payments
        SchedulePayment::create(['schedule_id' => $s1->id, 'payment_number' => SchedulePayment::generatePaymentNumber(), 'payment_type' => 'down_payment', 'amount' => 5000000, 'payment_date' => '2026-07-25', 'payment_method' => 'bank_transfer', 'reference_number' => 'TRF/0725/12345', 'status' => 'paid', 'created_by' => 1]);
        SchedulePayment::create(['schedule_id' => $s1->id, 'payment_number' => SchedulePayment::generatePaymentNumber(), 'payment_type' => 'installment', 'amount' => 4000000, 'payment_date' => '2026-08-01', 'payment_method' => 'qris', 'reference_number' => 'QRIS/0801/67890', 'status' => 'paid', 'created_by' => 1]);
        SchedulePayment::create(['schedule_id' => $s4->id, 'payment_number' => SchedulePayment::generatePaymentNumber(), 'payment_type' => 'settlement', 'amount' => 12000000, 'payment_date' => '2026-07-15', 'payment_method' => 'bank_transfer', 'reference_number' => 'TRF/0715/54321', 'status' => 'paid', 'created_by' => 1]);
        $this->command->info("Payments: 3 created");

        // 6. Status Histories
        ScheduleStatusHistory::create(['schedule_id' => $s1->id, 'old_status' => null, 'new_status' => 'pending', 'changed_by' => 1]);
        ScheduleStatusHistory::create(['schedule_id' => $s1->id, 'old_status' => 'pending', 'new_status' => 'confirmed', 'notes' => 'Dikonfirmasi via telepon', 'changed_by' => 1]);
        ScheduleStatusHistory::create(['schedule_id' => $s3->id, 'old_status' => null, 'new_status' => 'pending', 'changed_by' => 1]);
        ScheduleStatusHistory::create(['schedule_id' => $s3->id, 'old_status' => 'pending', 'new_status' => 'confirmed', 'changed_by' => 1]);
        ScheduleStatusHistory::create(['schedule_id' => $s3->id, 'old_status' => 'confirmed', 'new_status' => 'in_progress', 'notes' => 'Kegiatan berlangsung', 'changed_by' => 1]);
        ScheduleStatusHistory::create(['schedule_id' => $s4->id, 'old_status' => null, 'new_status' => 'pending', 'changed_by' => 1]);
        ScheduleStatusHistory::create(['schedule_id' => $s4->id, 'old_status' => 'pending', 'new_status' => 'confirmed', 'changed_by' => 1]);
        ScheduleStatusHistory::create(['schedule_id' => $s4->id, 'old_status' => 'confirmed', 'new_status' => 'in_progress', 'changed_by' => 1]);
        ScheduleStatusHistory::create(['schedule_id' => $s4->id, 'old_status' => 'in_progress', 'new_status' => 'completed', 'notes' => 'Selesai semua', 'changed_by' => 1]);
        ScheduleStatusHistory::create(['schedule_id' => $s5->id, 'old_status' => null, 'new_status' => 'pending', 'changed_by' => 1]);
        ScheduleStatusHistory::create(['schedule_id' => $s6->id, 'old_status' => null, 'new_status' => 'pending', 'changed_by' => 1]);
        ScheduleStatusHistory::create(['schedule_id' => $s6->id, 'old_status' => 'pending', 'new_status' => 'cancelled', 'notes' => 'Dibatalkan bentrok', 'changed_by' => 1]);
        ScheduleStatusHistory::create(['schedule_id' => $s7->id, 'old_status' => null, 'new_status' => 'draft', 'changed_by' => 1]);
        $this->command->info("StatusHistories: 13 created");

        $this->command->info("\n=== DATA DUMMY SELESAI ===");
        $this->command->info("Schedules: " . Schedule::count());
        $this->command->info("Estimations: " . PriceEstimation::count());
        $this->command->info("Bookings: " . Booking::count());
        $this->command->info("OpenTripRegs: " . OpenTripRegistration::count());
        $this->command->info("Payments: " . SchedulePayment::count());
        $this->command->info("StatusHistories: " . ScheduleStatusHistory::count());
    }
}