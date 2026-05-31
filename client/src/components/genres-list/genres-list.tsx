import { useEffect } from 'react';
import { Link, useSearchParams } from 'react-router-dom';
import cn from 'classnames';
import { useAppSelector, useAppDispatch } from '../../hooks/';
import { setActiveGenre } from '../../store/genre-data/genre-data';
import { getGenres, getActiveGenre } from '../../store/genre-data/selectors';
import { DEFAULT_GENRE } from '../../const';
import { resolveGenreFromSearch } from '../../services/genres';
import { capitalize } from '../../util';

function GenresList() {
  const dispatch = useAppDispatch();
  const activeGenre = useAppSelector(getActiveGenre);
  const genres = useAppSelector(getGenres);
  const [searchParams] = useSearchParams();
  const searchGenre = searchParams.get('genre');

  useEffect(() => {
    const resolvedGenre = resolveGenreFromSearch(genres, searchGenre);

    if (resolvedGenre === activeGenre) {
      return;
    }

    dispatch(setActiveGenre(resolvedGenre));
  }, [searchGenre, dispatch, genres, activeGenre]);

  return (
    <ul className="catalog__genres-list">
      {genres.slice(0, 10).map((genre) => (
        <li
          key={genre}
          className={cn('catalog__genres-item', {
            'catalog__genres-item--active': genre === activeGenre,
          })}
        >
          <Link
            to={genre === DEFAULT_GENRE ? '/' : `?genre=${encodeURIComponent(genre)}`}
            className="catalog__genres-link"
          >
            {capitalize(genre)}
          </Link>
        </li>
      ))}
    </ul>
  );
}

export default GenresList;
