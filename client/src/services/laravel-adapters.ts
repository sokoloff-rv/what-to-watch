import { Film } from '../types/film';
import { NewReview } from '../types/new-review';
import { Review } from '../types/review';
import { User } from '../types/user';
import {
  FALLBACK_AVATAR,
  getBackgroundFallback,
  getPosterFallback,
} from './fallback-assets';
import { BACKEND_ORIGIN } from './url';

export type LaravelUser = {
  id?: number | string;
  name?: string | null;
  email?: string | null;
  avatar?: string | null;
  role?: string | null;
  token?: string | null;
};

export type LaravelFilm = {
  id?: number | string;
  name?: string | null;
  poster_image?: string | null;
  preview_image?: string | null;
  background_image?: string | null;
  background_color?: string | null;
  video_link?: string | null;
  preview_video_link?: string | null;
  description?: string | null;
  rating?: number | string | null;
  director?: string | null;
  starring?: string[] | null;
  run_time?: number | string | null;
  genre?: string[] | string | null;
  released?: number | string | null;
  is_favorite?: boolean | null;
  user?: LaravelUser | null;
  imdb_id?: string | null;
  status?: string | null;
  scores_count?: number | string | null;
};

export type LaravelPromo = {
  film_id?: number | string;
  film?: LaravelFilm | null;
};

export type LaravelReview = {
  id?: number | string;
  text?: string | null;
  comment?: string | null;
  rating?: number | string | null;
  created_at?: string | null;
  date?: string | null;
  author_name?: string | null;
  user?: LaravelUser | null;
};

export type LaravelGenre = {
  id?: number | string;
  name?: string | null;
};

const isExternalUrl = (value: string) =>
  /^(https?:)?\/\//.test(value) ||
  value.startsWith('data:') ||
  value.startsWith('blob:');

const isUnavailableSeedImage = (value: string) => {
  try {
    const { hostname } = new URL(value);
    return hostname === 'via.placeholder.com' || hostname === 'placeholder.com';
  } catch {
    return false;
  }
};

const makeAssetUrl = (value: string | null | undefined, fallback = '') => {
  if (!value) {
    return fallback;
  }

  if (isUnavailableSeedImage(value)) {
    return fallback;
  }

  if (isExternalUrl(value)) {
    return value;
  }

  const path = value.replace(/^public\//, 'storage/').replace(/^\/+/, '');
  return `${BACKEND_ORIGIN}/${path}`;
};

const toStringArray = (value: string[] | string | null | undefined) => {
  if (Array.isArray(value)) {
    return value.filter(Boolean);
  }

  return value ? [value] : [];
};

export const mapLaravelUser = (user: LaravelUser | null | undefined): User => ({
  id: user?.id ? String(user.id) : '',
  avatarUrl: makeAssetUrl(user?.avatar, FALLBACK_AVATAR),
  email: user?.email || '',
  name: user?.name || '',
  role: user?.role || 'user',
  token: user?.token || '',
});

export const mapLaravelFilm = (
  film: LaravelFilm | null | undefined
): Film | null => {
  if (!film || !film.id) {
    return null;
  }

  const id = String(film.id);
  const genres = toStringArray(film.genre);
  const posterFallback = getPosterFallback(id);
  const backgroundFallback = getBackgroundFallback(id);
  const previewImage = makeAssetUrl(
    film.preview_image || film.poster_image,
    posterFallback
  );
  const posterImage = makeAssetUrl(film.poster_image || film.preview_image, previewImage);

  return {
    id,
    imdbId: film.imdb_id || '',
    status: film.status || 'ready',
    name: film.name || 'Untitled',
    posterImage,
    previewImage,
    backgroundImage: makeAssetUrl(film.background_image, backgroundFallback),
    backgroundColor: film.background_color || '#1f1d1d',
    videoLink: makeAssetUrl(film.video_link),
    previewVideoLink: makeAssetUrl(film.preview_video_link),
    description: film.description || '',
    rating: Number(film.rating || 0),
    director: film.director || '',
    starring: film.starring || [],
    runTime: Number(film.run_time || 0),
    genre: genres.join(', '),
    genres,
    released: Number(film.released || 0),
    isFavorite: Boolean(film.is_favorite),
    scoresCount: Number(film.scores_count || 0),
    user: film.user ? mapLaravelUser(film.user) : undefined,
  };
};

export const mapLaravelFilms = (films: LaravelFilm[] | null | undefined) =>
  (films || []).map(mapLaravelFilm).filter((film): film is Film => Boolean(film));

export const mapLaravelReview = (review: LaravelReview): Review => ({
  id: String(review.id || ''),
  comment: review.text || review.comment || '',
  date: review.created_at || review.date || new Date().toISOString(),
  rating: Number(review.rating || 0),
  user: {
    name: review.author_name || review.user?.name || 'Guest',
  },
});

export const mapLaravelReviews = (reviews: LaravelReview[] | null | undefined) =>
  (reviews || []).map(mapLaravelReview);

export const mapLaravelGenres = (genres: LaravelGenre[] | null | undefined) =>
  Array.from(
    new Set(
      (genres || [])
        .map((genre) => genre.name?.trim())
        .filter((genre): genre is string => Boolean(genre))
    )
  );

export const toLaravelReviewPayload = (review: NewReview) => ({
  text: review.comment,
  rating: review.rating,
});

export const toLaravelFilmPayload = (film: Film) => ({
  name: film.name,
  'poster_image': film.posterImage,
  'preview_image': film.previewImage || film.posterImage,
  'background_image': film.backgroundImage,
  'background_color': film.backgroundColor,
  'video_link': film.videoLink,
  'preview_video_link': film.previewVideoLink,
  description: film.description,
  director: film.director,
  starring: film.starring,
  genre: film.genres.length ? film.genres : toStringArray(film.genre),
  'run_time': film.runTime,
  released: film.released,
  'imdb_id': film.imdbId,
  status: film.status,
});

export const toLaravelRegisterPayload = (user: {
  name: string;
  email: string;
  password: string;
  avatar?: File;
}) => {
  const formData = new FormData();

  formData.append('name', user.name);
  formData.append('email', user.email);
  formData.append('password', user.password);

  if (user.avatar) {
    formData.append('avatar', user.avatar);
  }

  return formData;
};
