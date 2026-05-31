import { createSlice, PayloadAction } from '@reduxjs/toolkit';
import { NameSpace, DEFAULT_GENRE } from '../../const';
import { GenreState } from '../../types/state';

const initialState: GenreState = {
  activeGenre: DEFAULT_GENRE,
  genres: [DEFAULT_GENRE],
  filmsByGenre: [],
  isLoading: false,
};

export const genreData = createSlice({
  name: NameSpace.Genre,
  initialState,
  reducers: {
    setActiveGenre: (state, action: PayloadAction<string>) => {
      state.activeGenre = action.payload;
    },
    setGenres: (state, action: PayloadAction<string[]>) => {
      state.genres = [
        DEFAULT_GENRE,
        ...Array.from(new Set(action.payload.filter(Boolean))),
      ];
    },
    setFilmsByGenre: (state, action: PayloadAction<GenreState['filmsByGenre']>) => {
      state.filmsByGenre = action.payload;
    },
    setLoading: (state, action: PayloadAction<boolean>) => {
      state.isLoading = action.payload;
    },
  },
});

export const {
  setActiveGenre,
  setGenres,
  setFilmsByGenre,
  setLoading,
} = genreData.actions;
