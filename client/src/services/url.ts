export const BACKEND_URL = (
  process.env.REACT_APP_API_URL || 'https://whattowatch.sokoloff-rv.ru/api'
).replace(/\/$/, '');

export const BACKEND_ORIGIN = new URL(BACKEND_URL).origin;
