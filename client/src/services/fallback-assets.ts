export const FALLBACK_AVATAR = '/img/avatar.jpg';

export const FALLBACK_BACKGROUNDS = [
  '/img/bg-the-grand-budapest-hotel.jpg',
  '/img/bg-header.jpg',
];

export const FALLBACK_POSTERS = [
  '/img/the-grand-budapest-hotel-poster.jpg',
  '/img/bohemian-rhapsody.jpg',
  '/img/avatar.jpg',
  '/img/fantastic-beasts-the-crimes-of-grindelwald.jpg',
  '/img/dardjeeling-limited.jpg',
  '/img/macbeth.jpg',
  '/img/mindhunter.jpg',
  '/img/we-need-to-talk-about-kevin.jpg',
  '/img/seven-years-in-tibet.jpg',
  '/img/johnny-english.jpg',
  '/img/aviator.jpg',
  '/img/what-we-do-in-the-shadows.jpg',
  '/img/no-country-for-old-men.jpg',
  '/img/orlando.jpg',
  '/img/midnight-special.jpg',
  '/img/pulp-fiction.jpg',
  '/img/moonrise-kingdom.jpg',
  '/img/revenant.jpg',
  '/img/war-of-the-worlds.jpg',
  '/img/shutter-island.jpg',
  '/img/snatch.jpg',
];

export const DEFAULT_BACKGROUND_IMAGE = FALLBACK_BACKGROUNDS[0];
export const DEFAULT_POSTER_IMAGE = FALLBACK_POSTERS[0];

const getFallbackIndex = (seed: string, length: number) =>
  seed.split('').reduce((sum, char) => sum + char.charCodeAt(0), 0) % length;

export const getPosterFallback = (seed: string) =>
  FALLBACK_POSTERS[getFallbackIndex(seed, FALLBACK_POSTERS.length)];

export const getBackgroundFallback = (seed: string) =>
  FALLBACK_BACKGROUNDS[getFallbackIndex(seed, FALLBACK_BACKGROUNDS.length)];
