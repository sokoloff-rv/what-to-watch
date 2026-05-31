import {
  ImgHTMLAttributes,
  SyntheticEvent,
  useEffect,
  useState,
} from 'react';

type FallbackImageProps = ImgHTMLAttributes<HTMLImageElement> & {
  fallbackSrc: string;
};

function FallbackImage({
  src,
  fallbackSrc,
  alt = '',
  onError,
  ...props
}: FallbackImageProps) {
  const [currentSrc, setCurrentSrc] = useState(src || fallbackSrc);

  useEffect(() => {
    setCurrentSrc(src || fallbackSrc);
  }, [src, fallbackSrc]);

  const handleError = (evt: SyntheticEvent<HTMLImageElement, Event>) => {
    if (currentSrc !== fallbackSrc) {
      setCurrentSrc(fallbackSrc);
    }

    onError?.(evt);
  };

  return (
    <img
      {...props}
      src={currentSrc}
      alt={alt}
      onError={handleError}
    />
  );
}

export default FallbackImage;
