import { AxiosInstance } from 'axios';
import {
  fetchFilms,
  fetchFilmsByGenre,
  fetchGenres,
  fetchPromo,
  postReview,
  setFavorite,
} from './api-actions';
import { APIRoute, DEFAULT_GENRE } from '../const';

type TestThunk = (
  dispatch: jest.Mock,
  getState: jest.Mock,
  extra: AxiosInstance
) => Promise<unknown>;

const makeApi = () =>
  ({
    get: jest.fn(),
    post: jest.fn(),
    delete: jest.fn(),
  } as unknown as AxiosInstance);

const getMock = (api: AxiosInstance) => api.get as jest.Mock;
const postMock = (api: AxiosInstance) => api.post as jest.Mock;

const executeThunk = (thunk: TestThunk, api: AxiosInstance) => {
  const dispatch = jest.fn();

  return thunk(dispatch, jest.fn(), api).then(() => dispatch);
};

const findAction = (dispatch: jest.Mock, type: string) =>
  dispatch.mock.calls.map(([action]) => action).find((action) => action.type === type);

describe('API actions', () => {
  it('fetches films from the Laravel films endpoint and maps the response', async () => {
    const api = makeApi();
    getMock(api).mockResolvedValueOnce({
      data: [
        {
          id: 7,
          name: 'Film',
          'preview_image': 'preview.jpg',
          rating: 8,
          genre: ['drama'],
          'is_favorite': false,
        },
      ],
    });

    const dispatch = await executeThunk(fetchFilms() as unknown as TestThunk, api);
    const setFilmsAction = findAction(dispatch, 'FILMS/setFilms');

    expect(api.get).toHaveBeenCalledWith(APIRoute.Films);
    expect(setFilmsAction.payload).toEqual([
      expect.objectContaining({
        id: '7',
        name: 'Film',
        previewImage: 'https://whattowatch.sokoloff-rv.ru/preview.jpg',
        genre: 'drama',
        genres: ['drama'],
      }),
    ]);
  });

  it('passes genre as a Laravel query parameter', async () => {
    const api = makeApi();
    getMock(api).mockResolvedValueOnce({ data: [] });

    await executeThunk(
      fetchFilmsByGenre('comedy') as unknown as TestThunk,
      api
    );

    expect(api.get).toHaveBeenCalledWith(APIRoute.Films, {
      params: { genre: 'comedy' },
    });
  });

  it('fetches all films without a genre query for the default genre', async () => {
    const api = makeApi();
    getMock(api).mockResolvedValueOnce({ data: [] });

    await executeThunk(
      fetchFilmsByGenre(DEFAULT_GENRE) as unknown as TestThunk,
      api
    );

    expect(api.get).toHaveBeenCalledWith(APIRoute.Films);
  });

  it('fetches genres from Laravel and maps them to names', async () => {
    const api = makeApi();
    getMock(api).mockResolvedValueOnce({
      data: [
        { id: 1, name: 'incidunt' },
        { id: 2, name: 'maxime' },
        { id: 3, name: 'incidunt' },
      ],
    });

    const dispatch = await executeThunk(fetchGenres() as unknown as TestThunk, api);
    const setGenresAction = findAction(dispatch, 'GENRE/setGenres');

    expect(api.get).toHaveBeenCalledWith(APIRoute.Genres);
    expect(setGenresAction.payload).toEqual(['incidunt', 'maxime']);
  });

  it('fetches promo by resolving promo film_id through the films endpoint', async () => {
    const api = makeApi();
    getMock(api)
      .mockResolvedValueOnce({ data: { id: 1, 'film_id': 21 } })
      .mockResolvedValueOnce({
        data: {
          id: 21,
          name: 'Promo film',
          'preview_image': 'preview.jpg',
          genre: ['drama'],
        },
      });

    const dispatch = await executeThunk(fetchPromo() as unknown as TestThunk, api);
    const setPromoAction = findAction(dispatch, 'PROMO/setPromoFilm');

    expect(api.get).toHaveBeenNthCalledWith(1, APIRoute.Promo);
    expect(api.get).toHaveBeenNthCalledWith(2, `${APIRoute.Films}/21`);
    expect(setPromoAction.payload).toEqual(
      expect.objectContaining({
        id: '21',
        name: 'Promo film',
      })
    );
  });

  it('posts reviews to the film comments endpoint with Laravel field names', async () => {
    const api = makeApi();
    postMock(api).mockResolvedValueOnce({ data: {} });

    await executeThunk(
      postReview({
        id: '7',
        review: {
          comment: 'A review that is long enough for the backend validation.',
          rating: 9,
        },
      }) as unknown as TestThunk,
      api
    );

    expect(api.post).toHaveBeenCalledWith(`${APIRoute.Films}/7/comments`, {
      text: 'A review that is long enough for the backend validation.',
      rating: 9,
    });
  });

  it('toggles favorites through Laravel film favorite endpoints', async () => {
    const api = makeApi();
    postMock(api).mockResolvedValueOnce({ data: {} });
    getMock(api).mockResolvedValueOnce({
      data: {
        id: 7,
        name: 'Film',
        'preview_image': 'preview.jpg',
        'is_favorite': true,
      },
    });

    await executeThunk(
      setFavorite({ id: '7', status: 1 }) as unknown as TestThunk,
      api
    );

    expect(api.post).toHaveBeenCalledWith(`${APIRoute.Films}/7/favorite`);
    expect(api.get).toHaveBeenCalledWith(`${APIRoute.Films}/7`);
  });
});
