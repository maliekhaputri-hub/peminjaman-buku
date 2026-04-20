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
                'cover_image' => 'laravel.jpg',
            ],
            [
                'title' => 'PHP The Right Way',
                'author' => 'PHP Community',
                'isbn' => '9780988225284',
                'description' => 'Best practices for modern PHP development.',
                'stock' => 8,
                'cover_image' => 'php.jpg',
            ],
            [
                'title' => 'Clean Code',
                'author' => 'Robert C. Martin',
                'isbn' => '9780132350884',
                'description' => 'A Handbook of Agile Software Craftsmanship.',
                'stock' => 12,
                'cover_image' => 'clean_code.jpg',
            ],
            [
                'title' => 'Design Patterns',
                'author' => 'Gang of Four',
                'isbn' => '9780201633610',
                'description' => 'Elements of Reusable Object-Oriented Software.',
                'stock' => 6,
                'cover_image' => 'design_patterns.jpg',
            ],
            [
                'title' => 'JavaScript: The Definitive Guide',
                'author' => 'David Flanagan',
                'isbn' => '9780596805524',
                'description' => 'Activate Your Web Pages.',
                'stock' => 7,
                'cover_image' => 'javascript.jpg',
            ],
            [
                'title' => 'You Don\'t Know JS',
                'author' => 'Kyle Simpson',
                'isbn' => '9781491905052',
                'description' => 'ES6 & Beyond.',
                'stock' => 9,
                'cover_image' => 'ydkjs.jpg',
            ],
            [
                'title' => 'Refactoring',
                'author' => 'Martin Fowler',
                'isbn' => '9780134757599',
                'description' => 'Improving the Design of Existing Code.',
                'stock' => 5,
                'cover_image' => 'refactoring.jpg',
            ],
            [
                'title' => 'The Pragmatic Programmer',
                'author' => 'Andrew Hunt, David Thomas',
                'isbn' => '9780135957059',
                'description' => 'Your journey to mastery.',
                'stock' => 15,
                'cover_image' => 'pragmatic.jpg',
            ],
            [
                'title' => 'Domain-Driven Design',
                'author' => 'Eric Evans',
                'isbn' => '9780321125217',
                'description' => 'Tackling Complexity in the Heart of Software.',
                'stock' => 4,
                'cover_image' => 'ddd.jpg',
            ],
            [
                'title' => 'Building Microservices',
                'author' => 'Sam Newman',
                'isbn' => '9781492034015',
                'description' => 'Designing Fine-Grained Systems.',
                'stock' => 11,
                'cover_image' => 'microservices.jpg',
            ],
            // Novel buku jaman kekinian (contemporary Indonesian novels)
            [
                'title' => 'Laut Bercerita',
                'author' => 'Leila S. Chudori',
                'isbn' => '9789793069778',
                'description' => 'Novel tentang perjuangan aktivis di era Orde Baru.',
                'stock' => 8,
                'cover_image' => 'laut_bercerita.jpg',
            ],
            [
                'title' => 'Perahu Kertas',
                'author' => 'Dee Lestari',
                'isbn' => '9789793068009',
                'description' => 'Cerita cinta dan perjuangan seniman muda di kota besar.',
                'stock' => 12,
                'cover_image' => 'perahu_kertas.jpg',
            ],
            [
                'title' => 'Rantau 1 Muara',
                'author' => 'Ahmad Fuadi',
                'isbn' => '9786020393202',
                'description' => 'Trilogi petualangan pemuda dari dusun ke dunia.',
                'stock' => 10,
                'cover_image' => 'rantau_1_muara.jpg',
            ],
            [
                'title' => 'Pulang',
                'author' => 'Leila S. Chudori',
                'isbn' => '9786024240034',
                'description' => 'Kisah eksil politik pasca G30S.',
                'stock' => 6,
                'cover_image' => 'pulang.jpg',
            ],
            [
                'title' => 'Orang-orang Bloomington',
                'author' => 'Budi Darma',
                'isbn' => '9789794615991',
                'description' => 'Koleksi cerita pendek absurd modern.',
                'stock' => 7,
                'cover_image' => 'bloomington.jpg',
            ],
            [
                'title' => 'Gadis Kretek',
                'author' => 'Ratih Kumala',
                'isbn' => '9789793069495',
                'description' => 'Novel sejarah tentang industri kretek di Indonesia.',
                'stock' => 9,
                'cover_image' => 'gadis_kretek.jpg',
            ],
            [
                'title' => 'Bumi Manusia',
                'author' => 'Pramoedya Ananta Toer',
                'isbn' => '9789794331093',
                'description' => 'Karya klasik tentang cinta dan kolonialisme (modern edition).',
                'stock' => 15,
                'cover_image' => 'bumi_manusia.jpg',
            ],
        ];

        foreach ($books as $bookData) {
            Book::create($bookData);
        }
    }
}

