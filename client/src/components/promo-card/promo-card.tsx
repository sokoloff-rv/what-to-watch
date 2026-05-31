import { Link } from 'react-router-dom';
import Logo from '../../components/logo/logo';
import UserBlock from '../../components/user-block/user-block';
import MyListButton from '../my-list-button/my-list-button';
import { Film } from '../../types/film';
import { useAppSelector } from '../../hooks/';
import { getIsAuth } from '../../store/user-data/selectors';
import { AppRoute } from '../../const';
import FallbackImage from '../fallback-image/fallback-image';
import {
  DEFAULT_BACKGROUND_IMAGE,
  DEFAULT_POSTER_IMAGE,
} from '../../services/fallback-assets';

type PromoCardProps = {
  promoFilm: Film;
};

function PromoCard({ promoFilm }: PromoCardProps) {
  const isAuth = useAppSelector(getIsAuth);
  const {
    backgroundColor,
    backgroundImage,
    name,
    posterImage,
    genre,
    released,
    id,
  } = promoFilm;

  return (
    <section className="film-card" style={{ backgroundColor }}>
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
        <div className="film-card__info">
          <div className="film-card__poster">
            <FallbackImage
              src={posterImage}
              fallbackSrc={DEFAULT_POSTER_IMAGE}
              alt={name}
              width="218"
              height="327"
            />
          </div>

          <div className="film-card__desc">
            <h2 className="film-card__title">{name}</h2>
            <p className="film-card__meta">
              <span className="film-card__genre">{genre}</span>
              <span className="film-card__year">{released}</span>
            </p>

            <div className="film-card__buttons">
              <Link
                className="btn btn--more film-card__button"
                to={`${AppRoute.Film}/${promoFilm.id}`}
              >
                <span>More</span>
              </Link>
              {isAuth && <MyListButton id={id} />}
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}

export default PromoCard;
