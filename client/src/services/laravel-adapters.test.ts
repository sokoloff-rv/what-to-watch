import {
  mapLaravelFilm,
  mapLaravelReview,
  mapLaravelUser,
  toLaravelFilmPayload,
  toLaravelRegisterPayload,
  toLaravelReviewPayload,
} from './laravel-adapters';
import { Film } from '../types/film';

const makeFilm = (): Film => ({
  id: '7',
  imdbId: 'tt0111161',
  status: 'ready',
  name: 'The Grand Budapest Hotel',
  posterImage: 'https://example.com/poster.jpg',
  previewImage: 'https://example.com/preview.jpg',
  backgroundImage: 'https://example.com/bg.jpg',
  backgroundColor: '#ffffff',
  videoLink: 'https://example.com/video.mp4',
  previewVideoLink: 'https://example.com/preview.mp4',
  description: 'A film description.',
  rating: 8.1,
  director: 'Wes Anderson',
  starring: ['Ralph Fiennes'],
  runTime: 99,
  genre: 'comedy',
  genres: ['comedy'],
  released: 2014,
  isFavorite: false,
  scoresCount: 100,
});

describe('Laravel adapters', () => {
  it('maps snake_case Laravel film payloads to the client film shape', () => {
    expect(
      mapLaravelFilm({
        id: 7,
        'imdb_id': 'tt0111161',
        status: 'ready',
        name: 'The Grand Budapest Hotel',
        'poster_image': 'poster.jpg',
        'preview_image': 'preview.jpg',
        'background_image': 'background.jpg',
        'background_color': '#ffffff',
        'video_link': 'video.mp4',
        'preview_video_link': 'preview.mp4',
        description: 'A film description.',
        rating: '8.1',
        director: 'Wes Anderson',
        starring: ['Ralph Fiennes'],
        'run_time': '99',
        genre: ['comedy', 'drama'],
        released: '2014',
        'is_favorite': true,
        'scores_count': '100',
        user: {
          id: 5,
          name: 'Moderator',
          email: 'moderator@example.com',
          role: 'moderator',
        },
      })
    ).toMatchObject({
      id: '7',
      imdbId: 'tt0111161',
      status: 'ready',
      name: 'The Grand Budapest Hotel',
      posterImage:
        'https://whattowatch.sokoloff-rv.ru/poster.jpg',
      previewImage:
        'https://whattowatch.sokoloff-rv.ru/preview.jpg',
      backgroundImage:
        'https://whattowatch.sokoloff-rv.ru/background.jpg',
      backgroundColor: '#ffffff',
      videoLink:
        'https://whattowatch.sokoloff-rv.ru/video.mp4',
      previewVideoLink:
        'https://whattowatch.sokoloff-rv.ru/preview.mp4',
      rating: 8.1,
      runTime: 99,
      genre: 'comedy, drama',
      genres: ['comedy', 'drama'],
      released: 2014,
      isFavorite: true,
      scoresCount: 100,
      user: {
        id: '5',
        name: 'Moderator',
        email: 'moderator@example.com',
        role: 'moderator',
      },
    });
  });

  it('replaces unreachable seeded placeholder images with local assets', () => {
    expect(
      mapLaravelFilm({
        id: 21,
        name: 'Seeded film',
        'poster_image': 'https://via.placeholder.com/640x480.png/00ee00',
        'preview_image': 'https://via.placeholder.com/640x480.png/008899',
        'background_image': 'https://via.placeholder.com/640x480.png/0099bb',
      })
    ).toMatchObject({
      posterImage: expect.stringMatching(/^\/img\/.+\.(jpg|png)$/),
      previewImage: expect.stringMatching(/^\/img\/.+\.(jpg|png)$/),
      backgroundImage: expect.stringMatching(/^\/img\/.+\.jpg$/),
    });
  });

  it('maps Laravel review and user payloads', () => {
    expect(
      mapLaravelReview({
        id: 4,
        text: 'A very thoughtful review.',
        rating: '9',
        'created_at': '2026-05-30T20:00:00.000000Z',
        'author_name': 'Guest',
      })
    ).toEqual({
      id: '4',
      comment: 'A very thoughtful review.',
      rating: 9,
      date: '2026-05-30T20:00:00.000000Z',
      user: { name: 'Guest' },
    });

    expect(
      mapLaravelUser({
        id: 10,
        name: 'User',
        email: 'user@example.com',
        avatar: 'public/avatars/avatar.png',
        role: 'user',
      })
    ).toMatchObject({
      id: '10',
      name: 'User',
      email: 'user@example.com',
      avatarUrl:
        'https://whattowatch.sokoloff-rv.ru/storage/avatars/avatar.png',
      role: 'user',
    });
  });

  it('maps client payloads back to Laravel field names', () => {
    expect(toLaravelFilmPayload(makeFilm())).toEqual({
      name: 'The Grand Budapest Hotel',
      'poster_image': 'https://example.com/poster.jpg',
      'preview_image': 'https://example.com/preview.jpg',
      'background_image': 'https://example.com/bg.jpg',
      'background_color': '#ffffff',
      'video_link': 'https://example.com/video.mp4',
      'preview_video_link': 'https://example.com/preview.mp4',
      description: 'A film description.',
      director: 'Wes Anderson',
      starring: ['Ralph Fiennes'],
      genre: ['comedy'],
      'run_time': 99,
      released: 2014,
      'imdb_id': 'tt0111161',
      status: 'ready',
    });

    expect(toLaravelReviewPayload({ comment: 'Nice.', rating: 8 })).toEqual({
      text: 'Nice.',
      rating: 8,
    });
  });

  it('builds multipart registration payloads expected by Laravel', () => {
    const payload = toLaravelRegisterPayload({
      name: 'User',
      email: 'user@example.com',
      password: 'password',
    });

    expect(payload).toBeInstanceOf(FormData);
    expect(payload.get('name')).toBe('User');
    expect(payload.get('email')).toBe('user@example.com');
    expect(payload.get('password')).toBe('password');
  });
});
