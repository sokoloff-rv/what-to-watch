import { useReducer, Reducer } from 'react';
import Select from 'react-select';
import { DEFAULT_GENRE } from '../../const';
import { useAppSelector } from '../../hooks';
import { getGenres } from '../../store/genre-data/selectors';
import { Film } from '../../types/film';
import { FormAction, FormActionType } from '../../types/add-film';
import { addFilmFormReducer } from './add-film-form.reducer';

type AddFilmFormProps = {
  film: Film;
  onSubmit: (filmData: Film) => void;
};

function AddFilmForm({
  film,
  onSubmit,
}: AddFilmFormProps) {
  const backendGenres = useAppSelector(getGenres)
    .filter((genreItem) => genreItem !== DEFAULT_GENRE);
  const [filmData, dispatchFilmData] = useReducer<Reducer<Film, FormAction>>(
    addFilmFormReducer,
    film
  );
  const {
    imdbId,
    status,
    name,
    description,
    genre,
    released,
    starring,
    director,
    runTime,
    backgroundColor,
    backgroundImage,
    posterImage,
    videoLink,
    previewVideoLink
  } = filmData;
  const selectedGenre = genre.split(', ')[0];
  const genreOptions = Array.from(
    new Set([...backendGenres, ...filmData.genres, selectedGenre].filter(Boolean))
  );

  return (
    <form
      action="#"
      className="sign-in__form"
      onSubmit={(evt) => {
        evt.preventDefault();
        onSubmit(filmData);
      }}
    >
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
            defaultValue={imdbId}
            onChange={(evt) =>
              dispatchFilmData({
                type: FormActionType.setImdbId,
                payload: evt.target.value,
              })}
          />
        </div>
      </div>
      <div className="sign-in__fields">
        <div className="sign-in__field">
          <label className="sign-in__label" htmlFor="status">
            Status
          </label>
          <select
            className="sign-in__input"
            name="status"
            id="status"
            required
            defaultValue={status}
            onChange={(evt) =>
              dispatchFilmData({
                type: FormActionType.setStatus,
                payload: evt.target.value,
              })}
          >
            <option value="pending">Pending</option>
            <option value="moderate">Moderate</option>
            <option value="ready">Ready</option>
          </select>
        </div>
      </div>
      <div className="sign-in__fields">
        <div className="sign-in__field">
          <label className="sign-in__label" htmlFor="film-name">
            Film name
          </label>
          <input
            className="sign-in__input"
            type="text"
            placeholder="Film name"
            name="film-name"
            id="film-name"
            required
            minLength={2}
            maxLength={100}
            defaultValue={name}
            onChange={(evt) =>
              dispatchFilmData({
                type: FormActionType.setName,
                payload: evt.target.value,
              })}
          />
        </div>
      </div>
      <div className="sign-in__fields">
        <div className="sign-in__field">
          <label className="sign-in__label" htmlFor="description">
            Description
          </label>
          <textarea
            className="sign-in__input"
            placeholder="Description"
            name="description"
            id="description"
            required
            minLength={20}
            maxLength={1024}
            defaultValue={description}
            onChange={(evt) =>
              dispatchFilmData({
                type: FormActionType.setDescription,
                payload: evt.target.value,
              })}
          />
        </div>
      </div>
      <div className="sign-in__fields">
        <div className="sign-in__field">
          <label className="sign-in__label" htmlFor="posterImage">
            Poster image
          </label>
          <input
            className="sign-in__input"
            type="url"
            placeholder="Poster image"
            name="posterImage"
            id="posterImage"
            required
            defaultValue={posterImage}
            onChange={(evt) =>
              dispatchFilmData({
                type: FormActionType.setPosterImage,
                payload: evt.target.value,
              })}
          />
        </div>
      </div>
      <div className="sign-in__fields">
        <div className="sign-in__field">
          <label className="sign-in__label" htmlFor="genre">
            Genres
          </label>
          <Select
            className="sign-in__field react-select"
            classNamePrefix="react-select"
            name="genre"
            id="genre"
            defaultValue={{ value: selectedGenre, label: selectedGenre }}
            options={genreOptions.map((genreItem) => ({
              value: genreItem,
              label: genreItem,
            }))}
            onChange={(evt) =>
              dispatchFilmData({
                type: FormActionType.setGenre,
                payload: evt?.value || genre,
              })}
          />
        </div>
      </div>
      <div className="sign-in__fields">
        <div className="sign-in__field">
          <label className="sign-in__label" htmlFor="released">
            Year
          </label>
          <input
            className="sign-in__input"
            type="number"
            placeholder="2022"
            name="released"
            id="released"
            required
            defaultValue={released || ''}
            onChange={(evt) =>
              dispatchFilmData({
                type: FormActionType.setReleased,
                payload: evt.target.value,
              })}
          />
        </div>
      </div>
      <div className="sign-in__fields">
        <div className="sign-in__field">
          <label className="sign-in__label" htmlFor="starring">
            Starring (comma separated)
          </label>
          <input
            className="sign-in__input"
            type="text"
            placeholder="Star A, Star B"
            name="starring"
            id="starring"
            required
            defaultValue={starring.join(', ')}
            onChange={(evt) =>
              dispatchFilmData({
                type: FormActionType.setStarring,
                payload: evt.target.value.split(',').map((item) => item.trim()),
              })}
          />
        </div>
      </div>
      <div className="sign-in__fields">
        <div className="sign-in__field">
          <label className="sign-in__label" htmlFor="director">
            Director
          </label>
          <input
            className="sign-in__input"
            type="text"
            placeholder="Director"
            name="director"
            id="director"
            required
            minLength={2}
            maxLength={50}
            defaultValue={director}
            onChange={(evt) =>
              dispatchFilmData({
                type: FormActionType.setDirector,
                payload: evt.target.value,
              })}
          />
        </div>
      </div>
      <div className="sign-in__fields">
        <div className="sign-in__field">
          <label className="sign-in__label" htmlFor="runTime">
            Run time (in minutes)
          </label>
          <input
            className="sign-in__input"
            type="number"
            placeholder="90"
            name="runTime"
            id="runTime"
            required
            defaultValue={runTime || ''}
            onChange={(evt) =>
              dispatchFilmData({
                type: FormActionType.setRunTime,
                payload: evt.target.value,
              })}
          />
        </div>
      </div>
      <div className="sign-in__fields">
        <div className="sign-in__field">
          <label className="sign-in__label" htmlFor="backgroundColor">
            Background color (in hex)
          </label>
          <input
            className="sign-in__input"
            type="text"
            placeholder="#000000"
            name="backgroundColor"
            id="backgroundColor"
            required
            defaultValue={backgroundColor}
            onChange={(evt) =>
              dispatchFilmData({
                type: FormActionType.setBackgroundColor,
                payload: evt.target.value,
              })}
          />
        </div>
      </div>
      <div className="sign-in__fields">
        <div className="sign-in__field">
          <label className="sign-in__label" htmlFor="backgroundImage">
            Background image
          </label>
          <input
            className="sign-in__input"
            type="url"
            placeholder="Background image"
            name="backgroundImage"
            id="backgroundImage"
            required
            defaultValue={backgroundImage}
            onChange={(evt) =>
              dispatchFilmData({
                type: FormActionType.setBackgroundImage,
                payload: evt.target.value,
              })}
          />
        </div>
      </div>
      <div className="sign-in__fields">
        <div className="sign-in__field">
          <label className="sign-in__label" htmlFor="videoLink">
            Video
          </label>
          <input
            className="sign-in__input"
            type="url"
            placeholder="Video"
            name="videoLink"
            id="videoLink"
            required
            defaultValue={videoLink}
            onChange={(evt) =>
              dispatchFilmData({
                type: FormActionType.setVideoLink,
                payload: evt.target.value,
              })}
          />
        </div>
      </div>
      <div className="sign-in__fields">
        <div className="sign-in__field">
          <label className="sign-in__label" htmlFor="previewVideoLink">
            Preview video
          </label>
          <input
            className="sign-in__input"
            type="url"
            placeholder="Preview video"
            name="previewVideoLink"
            id="previewVideoLink"
            required
            defaultValue={previewVideoLink}
            onChange={(evt) =>
              dispatchFilmData({
                type: FormActionType.setPreviewVideoLink,
                payload: evt.target.value,
              })}
          />
        </div>
      </div>
      <div className="sign-in__submit">
        <button className="sign-in__btn" type="submit">
          Save
        </button>
      </div>
    </form>
  );
}

export default AddFilmForm;
