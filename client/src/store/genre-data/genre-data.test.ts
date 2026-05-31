import { DEFAULT_GENRE } from '../../const';
import { genreData, setGenres } from './genre-data';

describe('genreData reducer', () => {
  it('stores backend genres with the default genre first', () => {
    const state = genreData.reducer(
      undefined,
      setGenres(['maxime', 'ea', 'maxime', ''])
    );

    expect(state.genres).toEqual([DEFAULT_GENRE, 'maxime', 'ea']);
  });
});
