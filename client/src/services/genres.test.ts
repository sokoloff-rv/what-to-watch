import { DEFAULT_GENRE } from '../const';
import { resolveGenreFromSearch } from './genres';

describe('genres service', () => {
  it('resolves only genres known by the backend', () => {
    expect(resolveGenreFromSearch([DEFAULT_GENRE, 'maxime'], 'maxime')).toBe(
      'maxime'
    );

    expect(resolveGenreFromSearch([DEFAULT_GENRE, 'maxime'], 'comedy')).toBe(
      DEFAULT_GENRE
    );
  });
});
