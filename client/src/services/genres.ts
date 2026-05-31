import { DEFAULT_GENRE } from '../const';

export const resolveGenreFromSearch = (
  genres: string[],
  searchGenre: string | null
) => {
  if (!searchGenre) {
    return DEFAULT_GENRE;
  }

  return (
    genres.find(
      (genre) => genre.toLowerCase() === searchGenre.toLowerCase()
    ) || DEFAULT_GENRE
  );
};
