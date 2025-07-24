<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\ReminderSetting;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {

    $data = DB::table('data_kendaraan')
            ->select('*', DB::raw('DATEDIFF(jadwal_pajak, CURDATE()) AS selisih_hari'))
            ->get();

    $hari= [30,20,10];
    $found = false;

    foreach ($data as $item) {
        $selisih_hari = $item->selisih_hari;
        if (!in_array($selisih_hari, $hari)) {
            continue;
        }
        //dd($table);
        Artisan::call('mail:send', [
            'item' => json_encode($item)
        ]);

        Log::info(message: "Hari ini : , Kirim data reminder ke Email :".print_r($item, true));
        $found = true;

        $message = "Reminder Pembayaran Pajak!!!
        $selisih_hari Hari lagi dengan No Polisi :{$item->no_registrasi}";

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'target' => '085726772771',
                'message' => $message,
                'countryCode' => '62',
            ),
            CURLOPT_HTTPHEADER => array(
                'Authorization: t8tTJvq14vH7wVqyKCmh'
            ),
        ));

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            Log::error($error_msg);
        } else {
            Log::info($response);
        }
        Log::info(message: "Hari ini : , Kirim data reminder ke Whatsapp :".print_r($item, true));
    }

    if (!$found) {
        Log::info('Tidak ada selisih hari sesuai');
    }
})->dailyAt('08:20');


// Schedule::call(function () {
//     // Ambil pengaturan pertama untuk reminder
//     $setting = ReminderSetting::first();
//     if (!$setting) {
//         Log::warning('Reminder setting belum dikonfigurasi.');
//         return;
//     }

//     // Ambil data kendaraan
//     $data = DB::table('data_kendaraan')
//         ->select('*', DB::raw('DATEDIFF(jadwal_pajak, CURDATE()) AS selisih_hari'))
//         ->get();

//     // Ambil daftar hari sebelum jatuh tempo dari pengaturan atau default [30, 20, 10]
//     $hari = $setting->days_before ?? [30, 20, 10];
//     $found = false;

//     // Proses setiap data kendaraan
//     foreach ($data as $item) {
//         // Cek apakah selisih hari ada dalam pengaturan
//         if (!in_array($item->selisih_hari, $hari)) {
//             continue;
//         }

//         // Kirim email jika pengaturan email aktif
//         if ($setting->send_email) {
//             Artisan::call('mail:send', [
//                 'item' => json_encode($item)
//             ]);
//             Log::info("Kirim Email: " . print_r($item, true));
//         }

//         // Kirim WhatsApp jika pengaturan WhatsApp aktif
//         if ($setting->send_whatsapp) {
//             $message = "Reminder Pembayaran Pajak!!!\n{$item->selisih_hari} Hari lagi dengan No Polisi :{$item->no_registrasi}";

//             $curl = curl_init();
//             curl_setopt_array($curl, [
//                 CURLOPT_URL => 'https://api.fonnte.com/send',
//                 CURLOPT_RETURNTRANSFER => true,
//                 CURLOPT_POST => true,
//                 CURLOPT_POSTFIELDS => [
//                     'target' => '085726772771',
//                     'message' => $message,
//                     'countryCode' => '62',
//                 ],
//                 CURLOPT_HTTPHEADER => [
//                     'Authorization: t8tTJvq14vH7wVqyKCmh'
//                 ],
//             ]);
//             $response = curl_exec($curl);
//             if (curl_errno($curl)) {
//                 Log::error(curl_error($curl));
//             } else {
//                 Log::info("WhatsApp Sent: " . $response);
//             }
//         }

//         $found = true;
//     }

//     // Jika tidak ada data yang ditemukan, log pesan
//     if (!$found) {
//         Log::info('Tidak ada data reminder untuk dikirim hari ini.');
//     }
// })
// ->dailyAt($setting->reminder_time->toTimeString()); 