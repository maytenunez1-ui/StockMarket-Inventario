<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $hasUserRole = Schema::hasColumn('users', 'role');

        $adminData = [
            'name' => 'Administrador',
            'password' => Hash::make('password'),
        ];

        if ($hasUserRole) {
            $adminData['role'] = 'admin';
        }

        User::query()->updateOrCreate(
            ['email' => 'admin@supermercado.test'],
            $adminData
        );

        $customerData = [
            'name' => 'Cliente Demo',
            'password' => Hash::make('password'),
        ];

        if ($hasUserRole) {
            $customerData['role'] = 'user';
        }

        User::query()->updateOrCreate(
            ['email' => 'cliente@supermercado.test'],
            $customerData
        );

        $categories = collect([
            ['name' => 'Granos basicos', 'description' => 'Frijoles, arroz, maiz y productos esenciales de la cocina hondurena.'],
            ['name' => 'Lacteos hondurenos', 'description' => 'Quesos, crema y productos refrigerados nacionales.'],
            ['name' => 'Bebidas nacionales', 'description' => 'Cafe, horchata y bebidas populares de Honduras.'],
            ['name' => 'Panaderia y snacks', 'description' => 'Rosquillas, tajadas y antojos tradicionales.'],
            ['name' => 'Tortillas y maiz', 'description' => 'Productos de maiz para acompanar comidas catrachas.'],
        ])->mapWithKeys(function (array $category): array {
            return [
                $category['name'] => Category::query()->updateOrCreate(
                    ['slug' => Str::slug($category['name'])],
                    $category + ['slug' => Str::slug($category['name'])]
                ),
            ];
        });

        $brands = collect([
            ['full_name' => 'Cafe Lenca', 'nationality' => 'Honduras', 'biography' => 'Cafe de altura inspirado en las zonas cafetaleras hondurenas.'],
            ['full_name' => 'Catracho Basico', 'nationality' => 'Honduras', 'biography' => 'Marca para granos y abarrotes de consumo diario.'],
            ['full_name' => 'Lacteos Sula', 'nationality' => 'Honduras', 'biography' => 'Linea de lacteos hondurenos para la mesa familiar.'],
            ['full_name' => 'Maiz del Valle', 'nationality' => 'Honduras', 'biography' => 'Productos de maiz y tortillas frescas.'],
            ['full_name' => 'Antojitos HN', 'nationality' => 'Honduras', 'biography' => 'Snacks y panaderia tradicional hondurena.'],
            ['full_name' => 'Bebidas Catrachas', 'nationality' => 'Honduras', 'biography' => 'Bebidas tipicas listas para compartir.'],
        ])->mapWithKeys(fn (array $brand): array => [
            $brand['full_name'] => Author::query()->updateOrCreate(['full_name' => $brand['full_name']], $brand),
        ]);

        $providers = collect([
            ['name' => 'Distribuidora Central', 'country' => 'Honduras', 'website' => 'https://distribuidora-central.test'],
            ['name' => 'Mercados Frescos', 'country' => 'Honduras', 'website' => 'https://mercados-frescos.test'],
            ['name' => 'Suministros del Hogar', 'country' => 'Honduras', 'website' => 'https://suministros-hogar.test'],
        ])->mapWithKeys(function (array $provider): array {
            return [
                $provider['name'] => Publisher::query()->updateOrCreate(
                    ['slug' => Str::slug($provider['name'])],
                    $provider + ['slug' => Str::slug($provider['name'])]
                ),
            ];
        });

        $products = [
            [
                'title' => 'Cafe hondureno molido 1 libra',
                'isbn' => 'HON-001',
                'publication_year' => now()->year,
                'format' => 'hibrido',
                'stock' => 54,
                'summary' => 'Cafe molido de altura con aroma intenso, ideal para preparar cafe catracho en casa.',
                'brand' => 'Cafe Lenca',
                'provider' => 'Mercados Frescos',
                'categories' => ['Bebidas nacionales'],
            ],
            [
                'title' => 'Frijoles rojos hondurenos 2 libras',
                'isbn' => 'HON-002',
                'publication_year' => now()->year,
                'format' => 'hibrido',
                'stock' => 72,
                'summary' => 'Frijol rojo seleccionado para baleadas, casamientos y platos tipicos hondurenos.',
                'brand' => 'Catracho Basico',
                'provider' => 'Distribuidora Central',
                'categories' => ['Granos basicos'],
            ],
            [
                'title' => 'Mantequilla crema hondurena 450 g',
                'isbn' => 'HON-003',
                'publication_year' => now()->year,
                'format' => 'hibrido',
                'stock' => 38,
                'summary' => 'Crema espesa estilo hondureno para baleadas, tajadas, tortillas y desayunos.',
                'brand' => 'Lacteos Sula',
                'provider' => 'Distribuidora Central',
                'categories' => ['Lacteos hondurenos'],
            ],
            [
                'title' => 'Queso seco hondureno 1 libra',
                'isbn' => 'HON-004',
                'publication_year' => now()->year,
                'format' => 'hibrido',
                'stock' => 31,
                'summary' => 'Queso seco rallable con sabor tradicional para frijoles, enchiladas y tortillas.',
                'brand' => 'Lacteos Sula',
                'provider' => 'Distribuidora Central',
                'categories' => ['Lacteos hondurenos'],
            ],
            [
                'title' => 'Tortillas de maiz frescas paquete 20',
                'isbn' => 'HON-005',
                'publication_year' => now()->year,
                'format' => 'hibrido',
                'stock' => 95,
                'summary' => 'Tortillas de maiz suaves y frescas para acompanar almuerzos y cenas.',
                'brand' => 'Maiz del Valle',
                'provider' => 'Mercados Frescos',
                'categories' => ['Tortillas y maiz'],
            ],
            [
                'title' => 'Tajadas de platano verde 250 g',
                'isbn' => 'HON-006',
                'publication_year' => now()->year,
                'format' => 'hibrido',
                'stock' => 48,
                'summary' => 'Tajadas crujientes de platano verde, perfectas para merienda o acompanar comidas.',
                'brand' => 'Antojitos HN',
                'provider' => 'Suministros del Hogar',
                'categories' => ['Panaderia y snacks'],
            ],
            [
                'title' => 'Horchata hondurena concentrada 1 litro',
                'isbn' => 'HON-007',
                'publication_year' => now()->year,
                'format' => 'hibrido',
                'stock' => 42,
                'summary' => 'Bebida concentrada de horchata con sabor tradicional para servir bien fria.',
                'brand' => 'Bebidas Catrachas',
                'provider' => 'Distribuidora Central',
                'categories' => ['Bebidas nacionales'],
            ],
            [
                'title' => 'Rosquillas hondurenas bolsa 300 g',
                'isbn' => 'HON-008',
                'publication_year' => now()->year,
                'format' => 'hibrido',
                'stock' => 58,
                'summary' => 'Rosquillas de maiz y queso, ideales con cafe hondureno por la tarde.',
                'brand' => 'Antojitos HN',
                'provider' => 'Suministros del Hogar',
                'categories' => ['Panaderia y snacks', 'Tortillas y maiz'],
            ],
        ];

        foreach ($products as $productData) {
            $book = Book::query()->updateOrCreate(
                ['isbn' => $productData['isbn']],
                [
                    'author_id' => $brands[$productData['brand']]->id,
                    'publisher_id' => $providers[$productData['provider']]->id,
                    'title' => $productData['title'],
                    'slug' => Str::slug($productData['title']),
                    'publication_year' => $productData['publication_year'],
                    'format' => $productData['format'],
                    'stock' => $productData['stock'],
                    'summary' => $productData['summary'],
                    'is_active' => true,
                ]
            );

            $book->categories()->sync(
                collect($productData['categories'])->map(fn (string $name) => $categories[$name]->id)->all()
            );
        }
    }
}
