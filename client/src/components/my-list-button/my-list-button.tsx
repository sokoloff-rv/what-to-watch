import { setFavorite } from '../../store/api-actions';
import { getActiveFilm } from '../../store/film-data/selectors';
import { getIsFavorite as getIsFavoriteInFilms } from '../../store/films-data/selectors';
import { getIsFavorite as getIsFavoriteInFavorites } from '../../store/favorite-films-data/selectors';
import { useAppDispatch, useAppSelector } from '../../hooks/';

type MyListButtonProps = {
  id: string;
  isFavorite?: boolean;
};

function MyListButton({ id, isFavorite: initialIsFavorite }: MyListButtonProps) {
  const dispatch = useAppDispatch();
  const activeFilm = useAppSelector(getActiveFilm);
  const favoriteListFavorite = useAppSelector((state) =>
    getIsFavoriteInFavorites(state, id)
  );
  const listFavorite = useAppSelector((state) => getIsFavoriteInFilms(state, id));
  const isFavorite =
    favoriteListFavorite ??
    initialIsFavorite ??
    listFavorite ??
    (activeFilm?.id === id ? activeFilm.isFavorite : false);

  const handleClick = () => {
    dispatch(setFavorite({ id, status: isFavorite ? 0 : 1 }));
  };

  return (
    <button
      className="btn btn--list film-card__button"
      type="button"
      onClick={handleClick}
    >
      <svg viewBox="0 0 19 20" width="19" height="20">
        {isFavorite ? (
          <use xlinkHref="#in-list"></use>
        ) : (
          <use xlinkHref="#add"></use>
        )}
      </svg>
      <span>My list</span>
    </button>
  );
}

export default MyListButton;
