export enum AppRoute {
  Main = '/',
  Login = '/login',
  MyList = '/mylist',
  Film = '/films',
  AddReview = 'review',
  Player = '/player',
  Register = '/register',
  EditFilm = 'edit',
  AddFilm = '/create',
}

export enum AuthorizationStatus {
  Auth = 'AUTH',
  NoAuth = 'NO_AUTH',
  Unknown = 'UNKNOWN',
}

export enum Tab {
  Overview = 'Overview',
  Details = 'Details',
  Reviews = 'Reviews',
}

export const DEFAULT_GENRE = 'All genres';

export enum APIRoute {
  Films = '/films',
  User = '/user',
  Similar = '/similar',
  Promo = '/promo',
  Favorite = '/favorite',
  Comments = '/comments',
  Genres = '/genres',
  Login = '/login',
  Logout = '/logout',
  Register = '/register',
}

export enum UserRole {
  User = 'user',
  Moderator = 'moderator',
}

export enum NameSpace {
  Films = 'FILMS',
  Film = 'FILM',
  SimilarFilms = 'SIMILAR FILMS',
  Promo = 'PROMO',
  Reviews = 'REVIEWS',
  User = 'USER',
  FavoriteFilms = 'FAVORITE FILMS',
  Genre = 'GENRE',
}
