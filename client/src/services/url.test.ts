import { BACKEND_ORIGIN, BACKEND_URL } from './url';

describe('backend URL', () => {
  it('points to the public Laravel front controller API', () => {
    expect(BACKEND_URL).toBe('https://whattowatch.sokoloff-rv.ru/api');
    expect(BACKEND_ORIGIN).toBe('https://whattowatch.sokoloff-rv.ru');
  });
});
