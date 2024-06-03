import { LazyLoadImage } from 'react-lazy-load-image-component';
import 'react-lazy-load-image-component/src/effects/black-and-white.css';

export const PokemonImageComponent = ({ src, alt, width, height, classNames }) => (
    <LazyLoadImage
      src={src}
      alt={alt}
      height={height}
      width={width}
      className={classNames}
      effect="black-and-white"
        wrapperProps={{
            style: {transitionDelay: "0.1s"},
        }
    }/>
);