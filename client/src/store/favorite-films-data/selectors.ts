import { createSelector } from '@reduxjs/toolkit';
import { State, FavoriteFilmsState } from '../../types/state';
import { NameSpace } from '../../const';

export const getFavoriteFilms = createSelector(
  (state: State) => state[NameSpace.FavoriteFilms],
  (state: FavoriteFilmsState) => state.favoriteFilms
);

export const getIsFavorite = createSelector(
  [getFavoriteFilms, (_, id: string) => id],
  (films, id) => films.find((film) => film.id === id)?.isFavorite
);

export const getIsLoading = createSelector(
  (state: State) => state[NameSpace.FavoriteFilms],
  (state: FavoriteFilmsState) => state.isLoading
);
