import { FormEvent, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { toast } from 'react-toastify';
import Logo from '../../components/logo/logo';
import UserBlock from '../../components/user-block/user-block';
import Footer from '../../components/footer/footer';
import { AppRoute } from '../../const';
import { addFilm } from '../../store/api-actions';
import { useAppDispatch } from '../../hooks';

function AddFilmPage() {
  const dispatch = useAppDispatch();
  const navigate = useNavigate();
  const [imdbId, setImdbId] = useState('');

  const handleSubmit = async (evt: FormEvent<HTMLFormElement>) => {
    evt.preventDefault();

    const response = await dispatch(addFilm({ imdbId }));
    if (response.meta.requestStatus === 'rejected') {
      toast.error('Can\'t add film');
      return;
    }

    toast.success('Film import has been queued');
    navigate(AppRoute.Main);
  };

  return (
    <div className="user-page">
      <header className="page-header user-page__head">
        <Logo />
        <h1 className="page-title user-page__title">Add film</h1>
        <UserBlock />
      </header>
      <div className="sign-in user-page__content">
        <form className="sign-in__form" action="#" onSubmit={handleSubmit}>
          <div className="sign-in__fields">
            <div className="sign-in__field">
              <label className="sign-in__label" htmlFor="imdb-id">
                IMDb ID
              </label>
              <input
                className="sign-in__input"
                type="text"
                placeholder="tt0111161"
                name="imdb-id"
                id="imdb-id"
                required
                pattern="tt[0-9]{7,}"
                value={imdbId}
                onChange={(evt) => setImdbId(evt.target.value.trim())}
              />
            </div>
          </div>
          <div className="sign-in__submit">
            <button className="sign-in__btn" type="submit">
              Add film
            </button>
          </div>
        </form>
      </div>
      <Footer />
    </div>
  );
}

export default AddFilmPage;
