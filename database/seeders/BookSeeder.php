<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing books (ignore FK for now)
        DB::table('transactions')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Book::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $books = [


            // Programming books

            [
                'title' => 'Laravel 11 From Scratch',
                'author' => 'Laracasts',
                'isbn' => '1234567890',
                'description' => 'Complete Laravel tutorial from beginner to advanced.',
                'stock' => 10,
'cover_image' => null,
            ],
            [
                'title' => 'PHP The Right Way',
                'author' => 'PHP Community',
                'isbn' => '9780988225284',
                'description' => 'Best practices for modern PHP development.',
                'stock' => 8,
'cover_image' => null,
            ],
            [
                'title' => 'Clean Code',
                'author' => 'Robert C. Martin',
                'isbn' => '9780132350884',
                'description' => 'A Handbook of Agile Software Craftsmanship.',
                'stock' => 12,
'cover_image' => null,
            ],
            [
                'title' => 'Design Patterns',
                'author' => 'Gang of Four',
                'isbn' => '9780201633610',
                'description' => 'Elements of Reusable Object-Oriented Software.',
                'stock' => 6,
'cover_image' => null,
            ],
            [
                'title' => 'JavaScript: The Definitive Guide',
                'author' => 'David Flanagan',
                'isbn' => '9780596805524',
                'description' => 'Activate Your Web Pages.',
                'stock' => 7,
'cover_image' => null,
            ],
            [
                'title' => 'You Don\'t Know JS',
                'author' => 'Kyle Simpson',
                'isbn' => '9781491905052',
                'description' => 'ES6 & Beyond.',
                'stock' => 9,
                'cover_image' => null,
            ],
            [
                'title' => 'Refactoring',
                'author' => 'Martin Fowler',
                'isbn' => '9780134757599',
                'description' => 'Improving the Design of Existing Code.',
                'stock' => 5,
                'cover_image' => null,
            ],
            [
                'title' => 'The Pragmatic Programmer',
                'author' => 'Andrew Hunt, David Thomas',
                'isbn' => '9780135957059',
                'description' => 'Your journey to mastery.',
                'stock' => 15,
                'cover_image' => null,
            ],
            [
                'title' => 'Domain-Driven Design',
                'author' => 'Eric Evans',
                'isbn' => '9780321125217',
                'description' => 'Tackling Complexity in the Heart of Software.',
                'stock' => 4,
                'cover_image' => null,
            ],
            [
                'title' => 'Building Microservices',
                'author' => 'Sam Newman',
                'isbn' => '9781492034015',
                'description' => 'Designing Fine-Grained Systems.',
                'stock' => 11,
                'cover_image' => null,
            ],
            // Novel buku jaman kekinian (contemporary Indonesian novels)
            [
                'title' => 'Laut Bercerita',
                'author' => 'Leila S. Chudori',
                'isbn' => '9789793069778',
                'description' => 'Novel tentang perjuangan aktivis di era Orde Baru.',
                'stock' => 8,
                'cover_image' => null,
            ],
            [
                'title' => 'Perahu Kertas',
                'author' => 'Dee Lestari',
                'isbn' => '9789793068009',
                'description' => 'Cerita cinta dan perjuangan seniman muda di kota besar.',
                'stock' => 12,
                'cover_image' => null,
            ],
            [
                'title' => 'Rantau 1 Muara',
                'author' => 'Ahmad Fuadi',
                'isbn' => '9786020393202',
                'description' => 'Trilogi petualangan pemuda dari dusun ke dunia.',
                'stock' => 10,
                'cover_image' => 'https://1.bp.blogspot.com/-KNFLHt6QZiQ/WgfZCSx-yKI/AAAAAAAABJo/lWZQo2jp4HEn15qYl1sKFx8IjGS6bcA0wCLcBGAs/s1600/17382725.jpg',
            ],
            [
                'title' => 'Pulang',
                'author' => 'Leila S. Chudori',
                'isbn' => '9786024240034',
                'description' => 'Kisah eksil politik pasca G30S.',
                'stock' => 6,
                'cover_image' => 'https://bukukita.com/babacms/displaybuku/117225_f.jpg',
            ],
            [
                'title' => 'Orang-orang Bloomington',
                'author' => 'Budi Darma',
                'isbn' => '9789794615991',
                'description' => 'Koleksi cerita pendek absurd modern.',
                'stock' => 7,
                'cover_image' => 'https://bukukita.com/babacms/displaybuku/92747_f.jpg',
            ],
            [
                'title' => 'Gadis Kretek',
                'author' => 'Ratih Kumala',
                'isbn' => '9789793069495',
                'description' => 'Novel sejarah tentang industri kretek di Indonesia.',
                'stock' => 9,
                'cover_image' => 'https://cdn.gramedia.com/uploads/items/9789792281415_Gadis_Kretek_.jpg',
            ],
            [
                'title' => 'Bumi Manusia',
                'author' => 'Pramoedya Ananta Toer',
                'isbn' => '9789794331093',
                'description' => 'Karya klasik tentang cinta dan kolonialisme (modern edition).',
                'stock' => 15,
                'cover_image' => 'c:\Users\SMK MUTIARA BANDUNG\Downloads\BumiManusia-1181x1181.png',
            ],
        ];

        foreach ($books as $bookData) {
            Book::create($bookData);
        }
    }
}

