import { User } from './user';

export type Film = {
  id: string;
  imdbId: string;
  status: string;
  name: string;
  posterImage: string;
  previewImage: string;
  backgroundImage: string;
  backgroundColor: string;
  videoLink: string;
  previewVideoLink: string;
  description: string;
  rating: number;
  director: string;
  starring: string[];
  runTime: number;
  genre: string;
  genres: string[];
  released: number;
  isFavorite: boolean;
  scoresCount: number;
  user?: User;
};
