import { fireEvent, render, screen } from '@testing-library/react';
import Rating from './rating';

describe('Rating', () => {
  it('keeps visual star order aligned with submitted values', () => {
    const handleChange = jest.fn();

    const { rerender } = render(
      <Rating currentRating={0} onChange={handleChange} />
    );

    fireEvent.click(screen.getByLabelText('Rating 9'));

    expect(handleChange).toHaveBeenCalledWith(9);

    rerender(<Rating currentRating={9} onChange={handleChange} />);

    expect(screen.getByText('Rating 9')).toHaveClass('rating__label--active');
    expect(screen.getByText('Rating 10')).not.toHaveClass(
      'rating__label--active'
    );
  });
});
