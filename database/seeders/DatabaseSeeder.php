<?php

namespace Database\Seeders;

use App\Models\Collection;
use App\Models\Donation;
use App\Models\DonationAuditLog;
use App\Models\Lakou;
use App\Models\Locality;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DonationAuditLog::query()->delete();
        Donation::query()->delete();
        Collection::query()->delete();
        Lakou::query()->delete();
        Locality::query()->delete();

        $admin = User::query()->updateOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'Admin User',
            'password' => 'password',
            'is_admin' => true,
            'is_active' => true,
        ]);

        $collection = Collection::query()->create([
            'user_id' => $admin->id,
            'name' => 'Tomazo Pa Peri Maraton Pou legliz st anne',
            'description' => 'Seeded from the Tomazo Pap Peri St Anne donation spreadsheet.',
            'is_active' => true,
        ]);

        $donations = [
            ['Ducarmel Nicolas', null, 'Bouk', 200, true],
            ['Dieufort Brevil', null, 'Bouk', 300, true],
            ['Dodo Murano', null, 'Bouk', 150, true],
            ['Mme Dieuseul Brevil', null, 'Bouk', 100, true],
            ['Murat Brevil', null, 'Bouk', 100, true],
            ['Fecko', null, 'Bouk', 50, true],
            ['Vis prezidan Becco', null, 'Debas', 600, true],
            ['Walner', 'Ozias', 'Debas', 400, true],
            ['Gwoup JAC Tomazo', null, 'Debas', 300, true],
            ['Djo Bouki', 'Bouki', 'Debas', 200, false],
            ['Manita', 'Sauveur', 'Debas', 200, true],
            ['Rose-Laure August', 'Sauveur', 'Debas', 200, true],
            ['Viergema Deba', 'Sauveur', 'Debas', 200, true],
            ['VIP Tcho', 'Madan Blanc', 'Debas', 200, true],
            ['Wilbert Gelin', 'Wiyi', 'Debas', 200, true],
            ['Jacky Ozias', 'Ozias', 'Debas', 160, true],
            ['Ambassadeur Berthony', 'Dovik', 'Debas', 150, true],
            ['Ansyen Prezidan Rodeler', null, 'Debas', 100, true],
            ['Billie. Gelin', 'Wiyi', 'Debas', 100, true],
            ['Chrismène', null, 'Debas', 100, true],
            ['Clifrance Ozias', 'Ozias', 'Debas', 100, true],
            ['Eluxene François', 'Mimi', 'Debas', 100, true],
            ['Emilio', 'Mimi', 'Debas', 100, true],
            ['Jonas. Saintilus', 'Koki', 'Debas', 100, true],
            ['Journalis Nurmil', 'Bouki', 'Debas', 100, true],
            ['Judson lakou Ozias', 'Ozias', 'Debas', 100, true],
            ['Manman Baz', 'Tezi', 'Debas', 100, true],
            ['Myline lakou Ozias', 'Ozias', 'Debas', 100, true],
            ['Odette lakou Ozias', 'Ozias', 'Debas', 100, true],
            ['Onique François', 'Mimi', 'Debas', 100, true],
            ['Saintfonie. Auguste', null, 'Debas', 100, true],
            ['Yon pitit Deba', null, 'Debas', 100, true],
            ['Jacob lakou Ozias', 'Ozias', 'Debas', 70, false],
            ['Berry', 'Dovik', 'Debas', 50, true],
            ['Ketelie Occean', 'Dovik', 'Debas', 50, true],
            ['Lunet Coriolan', null, 'Debas', 50, true],
            ['Rosemène Charlerond', null, 'Debas', 50, true],
            ['Cinoïs', null, 'Debas', 200, true],
            ['Anayoo Cherie', null, 'Debas', 100, true],
            ['Barbe lakou Bouki', 'Bouki', 'Debas', 100, true],
            ['Djema François', null, 'Debas', 100, true],
            ['Kencel', null, 'Debas', 100, true],
            ['Madan Jonas Charite', null, 'Debas', 100, true],
            ['Dordy Show', null, 'Foudock', 500, true],
            ['Madame Murana Saint Louis', null, 'Hatte-Cadet', 200, true],
            ['Jonas Charite', null, 'Hatte-Cadet', 150, true],
            ['Bleus. Brenda', null, 'Hatte-Cadet', 100, true],
            ['Edmond Bleus', null, 'Hatte-Cadet', 100, true],
            ['Fedner Taxes', null, 'Hatte-Cadet', 100, true],
            ['Prezidan Yves Lawo', null, 'Lawo', 200, true],
            ['Angie', null, 'Lawo', 100, true],
            ['Onel Pierre', null, 'Lawo', 100, false],
            ['Janmen sou', null, 'Lawo', 50, true],
            ['Jean Claude (Tiyaton)', null, 'Lawo', 50, false],
            ["L'etang an avant", null, "L'Etang", 300, false],
            ['Wilbert Senoble', null, "L'Etang", 200, true],
            ['Donmarck Florestal', null, "L'Etang", 50, true],
            ['Muradieu Mede', null, "L'Etang", 50, true],
            ['Solène Guerrier', null, "L'Etang", 50, false],
            ['Glorious Raymond', null, 'Trou-Caïman', 150, true],
            ['Jacqueline Joseph', null, 'Trou-Caïman', 150, true],
            ['Armancie Luc', null, 'Trou-Caïman', 100, true],
            ['Cledanor Fileus', null, 'Trou-Caïman', 100, true],
            ['Gilbert et Madanm li', null, 'Trou-Caïman', 100, true],
            ['Jean Monplaisir', null, 'Trou-Caïman', 100, true],
            ['Marie Joseph', null, 'Trou-Caïman', 100, true],
            ['Marie Luc', null, 'Trou-Caïman', 100, true],
            ['Marie Monique', null, 'Trou-Caïman', 100, true],
            ['Marie Paul', null, 'Trou-Caïman', 100, true],
            ['Merinor Bosquet', null, 'Trou-Caïman', 100, true],
            ['Pierrot Raymond', null, 'Trou-Caïman', 100, true],
            ['Ovane Aceus', null, 'Trou-Caïman', 60, true],
            ['Alan Anna', null, 'Trou-Caïman', 50, true],
            ['Carnol Juisson', null, 'Trou-Caïman', 50, true],
            ['Clerfine Arbela', null, 'Trou-Caïman', 50, true],
            ['Vania Sagesse', null, 'Trou-Caïman', 50, true],
            ['Magalie Bosquet', null, 'Trou-Caïman', 50, true],
            ['Marie Merisier', null, 'Trou-Caïman', 50, true],
            ['Miradieu', null, 'Trou-Caïman', 50, true],
            ['Mirana Altine', null, 'Trou-Caïman', 50, true],
            ['Chrisnol Saintarmand', null, null, 200, true],
            ['Dordy Ducasse', null, null, 100, true],
            ['François Esdras', null, null, 50, true],
            ['Tanie Charles', null, null, 50, true],
        ];

        foreach ($donations as [$donorName, $lakou, $lokalite, $amount, $isPaid]) {
            Donation::query()->create([
                'collection_id' => $collection->id,
                'donor_name' => $donorName,
                'lakou' => $lakou,
                'lokalite' => $lokalite,
                'amount' => $amount,
                'is_paid' => $isPaid,
                'notes' => null,
            ]);
        }
    }
}
