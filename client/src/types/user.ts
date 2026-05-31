import { Token } from './token';

export type User = {
  id: string;
  avatarUrl: string;
  email: string;
  name: string;
  role: string;
  token: Token;
};
