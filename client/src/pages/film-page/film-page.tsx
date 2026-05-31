import { useEffect } from 'react';
import { Link, useParams } from 'react-router-dom';
import Logo from '../../components/logo/logo';
import UserBlock from '../../components/user-block/user-block';
import FilmsList from '../../components/films-list/films-list';
import TabsList from '../../components/tabs-list/tabs-list';
import Footer from '../../components/footer/footer';
import Spinner from '../../components/spinner/spinner';
import NotFoundPage from '../not-found-page/not-found-page';
import MyListButton from '../../components/my-list-button/my-list-button';
import { AppRoute } from '../../const';
import { useAppSelector, useAppDispatch } from '../../hooks/';
import { fetchFilm, fetchSimilarFilms } from '../../store/api-actions';
import {
  getActiveFilm,
  getIsLoading as getFilmIsLoading,
} from '../../store/film-data/selectors';
import {
  getSimilarFilms,
  getIsLoading as getSimilarFilmsIsLoading,
} from '../../store/similar-films-data/selectors';
import { getIsAuth, getIsModerator } from '../../store/user-data/selectors';
import FallbackImage from '../../components/fallback-image/fallback-image';
import {
  DEFAULT_BACKGROUND_IMAGE,
  DEFAULT_POSTER_IMAGE,
} from '../../services/fallback-assets';

function FilmPage() {
  const dispatch = useAppDispatch();
  const { id } = useParams();
  const film = useAppSelector(getActiveFilm);
  const isFilmLoading = useAppSelector(getFilmIsLoading);
  const similarFilms = useAppSelector(getSimilarFilms);
  const isSimilarFilmsLoading = useAppSelector(getSimilarFilmsIsLoading);
  const isAuth = useAppSelector(getIsAuth);
  const isModerator = useAppSelector(getIsModerator);

  useEffect(() => {
    if (!id) {
      return;
    }

    dispatch(fetchFilm(id));
    dispatch(fetchSimilarFilms(id));
  }, [dispatch, id]);

  if (isFilmLoading || isSimilarFilmsLoading) {
    return <Spinner />;
  }

  if (!film || !id) {
    return <NotFoundPage />;
  }

  const {
    name,
    genre,
    released,
    posterImage,
    backgroundImage,
    backgroundColor,
  } = film;

  return (
    <>
      <section
        className="film-card film-card--full"
        style={{ backgroundColor }}
      >
        <div className="film-card__hero">
          <div className="film-card__bg">
            <FallbackImage
              src={backgroundImage}
              fallbackSrc={DEFAULT_BACKGROUND_IMAGE}
              alt={name}
            />
          </div>

          <h1 className="visually-hidden">WTW</h1>

          <header className="page-header film-card__head">
            <Logo />
            <UserBlock />
          </header>

          <div className="film-card__wrap">
            <div className="film-card__desc">
              <h2 className="film-card__title">{name}</h2>
              <p className="film-card__meta">
                <span className="film-card__genre">{genre}</span>
                <span className="film-card__year">{released}</span>
              </p>

              {(isAuth || isModerator) && (
                <div className="film-card__buttons">
                  {isAuth && <MyListButton id={id} />}
                  {isAuth && (
                    <Link
                      to={`${AppRoute.Film}/${id}/${AppRoute.AddReview}`}
                      className="btn film-card__button"
                    >
                      Add review
                    </Link>
                  )}
                  {isModerator && (
                    <Link
                      to={`${AppRoute.Film}/${id}/${AppRoute.EditFilm}`}
                      className="btn film-card__button"
                    >
                      Edit Film
                    </Link>
                  )}
                </div>
              )}
            </div>
          </div>
        </div>

        <div className="film-card__wrap film-card__translate-top">
          <div className="film-card__info">
            <div className="film-card__poster film-card__poster--big">
              <FallbackImage
                src={posterImage}
                fallbackSrc={DEFAULT_POSTER_IMAGE}
                alt={name}
                width="218"
                height="327"
              />
            </div>

            <div className="film-card__desc">
              <TabsList film={film} />
            </div>
          </div>
        </div>
      </section>

      <div className="page-content">
        <section className="catalog catalog--like-this">
          <h2 className="catalog__title">More like this</h2>

          <FilmsList films={similarFilms.slice(0, 4)} withVideo={false} />
        </section>

        <Footer />
      </div>
    </>
  );
}

export default FilmPage;
