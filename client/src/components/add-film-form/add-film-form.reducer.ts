import { FormAction, FormActionType } from '../../types/add-film';
import { Film } from '../../types/film';

export function addFilmFormReducer(state: Film, action: FormAction): Film {
  const { type, payload } = action;
  const value = payload as string;
  switch (type) {
    case FormActionType.setImdbId:
      return { ...state, imdbId: value };
    case FormActionType.setStatus:
      return { ...state, status: value };
    case FormActionType.setName:
      return { ...state, name: value };
    case FormActionType.setDescription:
      return { ...state, description: value };
    case FormActionType.setPosterImage:
      return { ...state, posterImage: value };
    case FormActionType.setBackgroundImage:
      return { ...state, backgroundImage: value };
    case FormActionType.setBackgroundColor:
      return { ...state, backgroundColor: value };
    case FormActionType.setVideoLink:
      return { ...state, videoLink: value };
    case FormActionType.setPreviewVideoLink:
      return { ...state, previewVideoLink: value };
    case FormActionType.setDirector:
      return { ...state, director: value };
    case FormActionType.setGenre:
      return { ...state, genre: value, genres: [value] };
    case FormActionType.setRunTime:
      return { ...state, runTime: Number(value) };
    case FormActionType.setReleased:
      return { ...state, released: Number(value) };
    case FormActionType.setStarring:
      return { ...state, starring: payload as string[] };
    default:
      return state;
  }
}
