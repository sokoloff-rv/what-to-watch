<?php

namespace Database\Seeders;

use App\Models\Actor;
use App\Models\Film;
use App\Models\Genre;
use App\Models\Promo;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DemoCatalogSeeder extends Seeder
{
    private const IMAGE_FALLBACK_API_KEY = 'thewdb';
    private const BACKGROUND_IMAGE_HEIGHT = 1500;

    private const GENRE_FILMS = [
        'Drama' => [
            'tt0111161' => 'The Shawshank Redemption',
            'tt0068646' => 'The Godfather',
            'tt0109830' => 'Forrest Gump',
            'tt0120815' => 'Saving Private Ryan',
            'tt0167260' => 'The Lord of the Rings: The Return of the King',
            'tt0108052' => 'Schindler\'s List',
            'tt0816692' => 'Interstellar',
            'tt0137523' => 'Fight Club',
            'tt0120737' => 'The Lord of the Rings: The Fellowship of the Ring',
            'tt1375666' => 'Inception',
            'tt2582802' => 'Whiplash',
            'tt6751668' => 'Parasite',
            'tt0081505' => 'The Shining',
            'tt0110912' => 'Pulp Fiction',
            'tt0172495' => 'Gladiator',
            'tt1853728' => 'Django Unchained',
        ],
        'Comedy' => [
            'tt0107048' => 'Groundhog Day',
            'tt0114709' => 'Toy Story',
            'tt0266543' => 'Finding Nemo',
            'tt0118715' => 'The Big Lebowski',
            'tt0120382' => 'The Truman Show',
            'tt0264464' => 'Catch Me If You Can',
            'tt1049413' => 'Up',
            'tt2096673' => 'Inside Out',
            'tt0317705' => 'The Incredibles',
            'tt0338013' => 'Eternal Sunshine of the Spotless Mind',
            'tt2278388' => 'The Grand Budapest Hotel',
            'tt0109686' => 'Dumb and Dumber',
            'tt0098635' => 'When Harry Met Sally...',
            'tt0365748' => 'Shaun of the Dead',
            'tt0467406' => 'Juno',
            'tt0119217' => 'Good Will Hunting',
        ],
        'Crime' => [
            'tt0068646' => 'The Godfather',
            'tt0071562' => 'The Godfather Part II',
            'tt0110912' => 'Pulp Fiction',
            'tt0102926' => 'The Silence of the Lambs',
            'tt0114369' => 'Se7en',
            'tt0137523' => 'Fight Club',
            'tt0407887' => 'The Departed',
            'tt0468569' => 'The Dark Knight',
            'tt7286456' => 'Joker',
            'tt0114814' => 'The Usual Suspects',
            'tt0209144' => 'Memento',
            'tt0105236' => 'Reservoir Dogs',
            'tt0264464' => 'Catch Me If You Can',
            'tt0099685' => 'Goodfellas',
            'tt1853728' => 'Django Unchained',
            'tt0110413' => 'Leon: The Professional',
        ],
        'Action' => [
            'tt0133093' => 'The Matrix',
            'tt0468569' => 'The Dark Knight',
            'tt1375666' => 'Inception',
            'tt0080684' => 'Star Wars: Episode V - The Empire Strikes Back',
            'tt0120737' => 'The Lord of the Rings: The Fellowship of the Ring',
            'tt0167260' => 'The Lord of the Rings: The Return of the King',
            'tt0172495' => 'Gladiator',
            'tt0848228' => 'The Avengers',
            'tt4154756' => 'Avengers: Infinity War',
            'tt4154796' => 'Avengers: Endgame',
            'tt1392190' => 'Mad Max: Fury Road',
            'tt0361748' => 'Inglourious Basterds',
            'tt1853728' => 'Django Unchained',
            'tt0110413' => 'Leon: The Professional',
            'tt0088763' => 'Back to the Future',
            'tt0090605' => 'Aliens',
        ],
        'Sci-Fi' => [
            'tt0816692' => 'Interstellar',
            'tt1375666' => 'Inception',
            'tt0133093' => 'The Matrix',
            'tt0088763' => 'Back to the Future',
            'tt0083658' => 'Blade Runner',
            'tt0080684' => 'Star Wars: Episode V - The Empire Strikes Back',
            'tt0119116' => 'The Fifth Element',
            'tt0119654' => 'Men in Black',
            'tt1454468' => 'Gravity',
            'tt3659388' => 'The Martian',
            'tt2543164' => 'Arrival',
            'tt1392190' => 'Mad Max: Fury Road',
            'tt0114746' => 'Twelve Monkeys',
            'tt0470752' => 'Ex Machina',
            'tt0078748' => 'Alien',
            'tt0088247' => 'The Terminator',
        ],
        'Horror' => [
            'tt0081505' => 'The Shining',
            'tt0078748' => 'Alien',
            'tt0102926' => 'The Silence of the Lambs',
            'tt0054215' => 'Psycho',
            'tt0070047' => 'The Exorcist',
            'tt0084787' => 'The Thing',
            'tt0365748' => 'Shaun of the Dead',
            'tt5052448' => 'Get Out',
            'tt7784604' => 'Hereditary',
            'tt6644200' => 'A Quiet Place',
            'tt1179933' => '10 Cloverfield Lane',
            'tt0090605' => 'Aliens',
            'tt1396484' => 'It',
            'tt8772262' => 'Midsommar',
            'tt10638522' => 'Talk to Me',
            'tt0088247' => 'The Terminator',
        ],
        'Romance' => [
            'tt0120338' => 'Titanic',
            'tt0338013' => 'Eternal Sunshine of the Spotless Mind',
            'tt0112471' => 'Before Sunrise',
            'tt0381681' => 'Before Sunset',
            'tt0098635' => 'When Harry Met Sally...',
            'tt0109830' => 'Forrest Gump',
            'tt0118799' => 'Life Is Beautiful',
            'tt1045658' => 'Silver Linings Playbook',
            'tt3783958' => 'La La Land',
            'tt5726616' => 'Call Me by Your Name',
            'tt0119217' => 'Good Will Hunting',
            'tt1343092' => 'The Great Gatsby',
            'tt0117509' => 'Romeo + Juliet',
            'tt0467406' => 'Juno',
            'tt2582846' => 'The Fault in Our Stars',
            'tt0120382' => 'The Truman Show',
        ],
        'Family' => [
            'tt0114709' => 'Toy Story',
            'tt0266543' => 'Finding Nemo',
            'tt1049413' => 'Up',
            'tt2096673' => 'Inside Out',
            'tt0317705' => 'The Incredibles',
            'tt0910970' => 'WALL-E',
            'tt0382932' => 'Ratatouille',
            'tt2294629' => 'Frozen',
            'tt2948356' => 'Zootopia',
            'tt0245429' => 'Spirited Away',
            'tt0110357' => 'The Lion King',
            'tt1979376' => 'Toy Story 4',
            'tt0198781' => 'Monsters, Inc.',
            'tt0398286' => 'Tangled',
            'tt3521164' => 'Moana',
            'tt0103639' => 'Aladdin',
        ],
    ];

    private const BACKGROUND_COLORS = [
        '#241711',
        '#1f2937',
        '#111827',
        '#2f1d1d',
        '#1d2a2f',
        '#2a1d2f',
        '#2f281d',
        '#1d2f24',
    ];

    private const REVIEW_TEXTS = [
        'A confident classic that still works beautifully as a recommendation for a quiet evening.',
        'Strong pacing, memorable performances, and enough craft to make the film easy to revisit.',
        'A reliable audience pick: accessible, polished, and immediately recognizable.',
        'The kind of film that makes a demo catalog feel alive rather than randomly generated.',
    ];

    public function run(): void
    {
        $this->clearDemoTables();

        $users = $this->createDemoUsers();
        $genres = $this->createGenres();
        $filmGenres = $this->buildFilmGenres();
        $films = $this->createFilms($filmGenres, $genres);

        $this->createPromo($films);
        $this->createFavorites($users, $films);
        $this->createComments($users, $films);
    }

    private function clearDemoTables(): void
    {
        Schema::disableForeignKeyConstraints();

        DB::table('promo')->truncate();
        DB::table('comments')->truncate();
        DB::table('user_favorites')->truncate();
        DB::table('actor_film')->truncate();
        DB::table('film_genre')->truncate();
        Actor::truncate();
        Genre::truncate();
        Film::truncate();
        User::truncate();

        Schema::enableForeignKeyConstraints();
    }

    /**
     * @return array<string, User>
     */
    private function createDemoUsers(): array
    {
        return [
            'user' => User::create([
                'name' => 'Demo User',
                'email' => 'user@example.com',
                'password' => Hash::make('password'),
                'avatar' => 'https://i.pravatar.cc/126?img=12',
                'role' => User::ROLE_USER,
            ]),
            'moderator' => User::create([
                'name' => 'Demo Moderator',
                'email' => 'moderator@example.com',
                'password' => Hash::make('password'),
                'avatar' => 'https://i.pravatar.cc/126?img=32',
                'role' => User::ROLE_MODERATOR,
            ]),
        ];
    }

    /**
     * @return array<string, Genre>
     */
    private function createGenres(): array
    {
        return collect(array_keys(self::GENRE_FILMS))
            ->mapWithKeys(fn (string $name) => [$name => Genre::create(['name' => $name])])
            ->all();
    }

    /**
     * @return array<string, array{title: string, genres: array<int, string>}>
     */
    private function buildFilmGenres(): array
    {
        $films = [];

        foreach (self::GENRE_FILMS as $genre => $genreFilms) {
            foreach ($genreFilms as $imdbId => $title) {
                $films[$imdbId] ??= [
                    'title' => $title,
                    'genres' => [],
                ];

                $films[$imdbId]['genres'][] = $genre;
            }
        }

        foreach ($films as &$film) {
            $film['genres'] = array_values(array_unique($film['genres']));
        }

        return $films;
    }

    /**
     * @param array<string, array{title: string, genres: array<int, string>}> $filmGenres
     * @param array<string, Genre> $genres
     * @return array<string, Film>
     */
    private function createFilms(array $filmGenres, array $genres): array
    {
        $films = [];
        $index = 0;

        foreach ($filmGenres as $imdbId => $fallbackData) {
            $movieData = $this->fetchMovieData($imdbId);
            $poster = $this->posterUrl($imdbId, $movieData);
            $backgroundImage = $this->backgroundImageUrl($imdbId, $poster);

            $film = Film::create([
                'name' => $movieData['Title'] ?? $fallbackData['title'],
                'poster_image' => $poster,
                'preview_image' => $poster,
                'background_image' => $backgroundImage,
                'background_color' => self::BACKGROUND_COLORS[$index % count(self::BACKGROUND_COLORS)],
                'video_link' => null,
                'preview_video_link' => null,
                'description' => $this->description($movieData),
                'director' => $movieData['Director'] ?? 'Unknown',
                'released' => $this->year($movieData['Year'] ?? null),
                'run_time' => $this->runtime($movieData['Runtime'] ?? null),
                'rating' => $this->rating($movieData['imdbRating'] ?? null),
                'scores_count' => $this->votes($movieData['imdbVotes'] ?? null),
                'imdb_id' => $imdbId,
                'status' => Film::STATUS_READY,
            ]);

            $film->genres()->sync(
                collect($fallbackData['genres'])->map(fn (string $name) => $genres[$name]->id)
            );

            $film->actors()->sync($this->actorIds($movieData['Actors'] ?? ''));

            $films[$imdbId] = $film;
            $index++;
        }

        return $films;
    }

    private function fetchMovieData(string $imdbId): array
    {
        foreach ($this->omdbApiKeys() as $apiKey) {
            $response = Http::timeout(8)->get('https://www.omdbapi.com/', [
                'apikey' => $apiKey,
                'i' => $imdbId,
                'plot' => 'short',
            ]);

            if ($response->ok() && $response->json('Response') === 'True') {
                return $response->json();
            }
        }

        return [];
    }

    /**
     * @return array<int, string>
     */
    private function omdbApiKeys(): array
    {
        return array_values(array_unique(array_filter([
            config('services.omdb.api_key'),
            self::IMAGE_FALLBACK_API_KEY,
        ])));
    }

    private function posterUrl(string $imdbId, array $movieData): string
    {
        if (($movieData['Poster'] ?? 'N/A') !== 'N/A') {
            return $movieData['Poster'];
        }

        return $this->omdbImageUrl($imdbId, 900);
    }

    private function omdbImageUrl(string $imdbId, int $height): string
    {
        return sprintf(
            'https://img.omdbapi.com/?i=%s&h=%d&apikey=%s',
            $imdbId,
            $height,
            $this->omdbImageApiKey()
        );
    }

    private function omdbImageApiKey(): string
    {
        return config('services.omdb.api_key') ?: self::IMAGE_FALLBACK_API_KEY;
    }

    private function backgroundImageUrl(string $imdbId, string $poster): string
    {
        if (str_contains($poster, 'm.media-amazon.com')) {
            return preg_replace(
                '/\._V1.*\.jpg$/',
                '._V1_SX' . self::BACKGROUND_IMAGE_HEIGHT . '.jpg',
                $poster
            ) ?: $poster;
        }

        return $this->omdbImageUrl($imdbId, self::BACKGROUND_IMAGE_HEIGHT);
    }

    private function description(array $movieData): string
    {
        $plot = $movieData['Plot'] ?? null;

        if ($plot && $plot !== 'N/A') {
            return $plot;
        }

        return 'A well-known film included in the What to Watch demo catalog.';
    }

    private function year(?string $year): int
    {
        return (int) Str::before($year ?: '2000', '-');
    }

    private function runtime(?string $runtime): int
    {
        if (!$runtime || $runtime === 'N/A') {
            return 100;
        }

        return (int) $runtime;
    }

    private function rating(?string $rating): float
    {
        if (!$rating || $rating === 'N/A') {
            return 0;
        }

        return (float) $rating;
    }

    private function votes(?string $votes): int
    {
        if (!$votes || $votes === 'N/A') {
            return 0;
        }

        return (int) str_replace(',', '', $votes);
    }

    /**
     * @return array<int, int>
     */
    private function actorIds(string $actors): array
    {
        if (!$actors || $actors === 'N/A') {
            return [];
        }

        return collect(explode(',', $actors))
            ->map(fn (string $name) => trim($name))
            ->filter()
            ->map(fn (string $name) => Actor::firstOrCreate(['name' => $name])->id)
            ->all();
    }

    /**
     * @param array<string, Film> $films
     */
    private function createPromo(array $films): void
    {
        $promoFilm = $films['tt0816692'] ?? reset($films);

        Promo::create(['film_id' => $promoFilm->id]);
    }

    /**
     * @param array<string, User> $users
     * @param array<string, Film> $films
     */
    private function createFavorites(array $users, array $films): void
    {
        $favoriteIds = collect(array_slice($films, 0, 12))
            ->map(fn (Film $film) => $film->id)
            ->all();

        $users['user']->favoriteFilms()->sync($favoriteIds);
    }

    /**
     * @param array<string, User> $users
     * @param array<string, Film> $films
     */
    private function createComments(array $users, array $films): void
    {
        foreach (array_values(array_slice($films, 0, 24)) as $index => $film) {
            DB::table('comments')->insert([
                'user_id' => $users[$index % 2 === 0 ? 'user' : 'moderator']->id,
                'film_id' => $film->id,
                'comment_id' => null,
                'text' => self::REVIEW_TEXTS[$index % count(self::REVIEW_TEXTS)],
                'rating' => max(1, min(10, (int) round($film->rating ?: 8))),
                'is_external' => false,
                'created_at' => now()->subDays($index + 1),
                'updated_at' => now()->subDays($index + 1),
            ]);
        }
    }
}
