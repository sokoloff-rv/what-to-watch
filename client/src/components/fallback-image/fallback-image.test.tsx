import { fireEvent, render, screen } from '@testing-library/react';
import FallbackImage from './fallback-image';

describe('FallbackImage', () => {
  it('uses fallback source when image loading fails', () => {
    render(
      <FallbackImage
        src="https://example.com/missing.jpg"
        fallbackSrc="/img/the-grand-budapest-hotel-poster.jpg"
        alt="Film poster"
      />
    );

    const image = screen.getByAltText('Film poster');

    expect(image).toHaveAttribute('src', 'https://example.com/missing.jpg');

    fireEvent.error(image);

    expect(image).toHaveAttribute(
      'src',
      '/img/the-grand-budapest-hotel-poster.jpg'
    );
  });
});
