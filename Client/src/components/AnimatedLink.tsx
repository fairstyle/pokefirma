import { flushSync } from 'react-dom';
import { useNavigate } from 'react-router-dom';

export const AnimatedLink = ({ to, children }) => {
    const navigate = useNavigate();
  
    return (
      <a
        href={to}
        onClick={(ev) => {
          ev.preventDefault();
          document.startViewTransition(() => {
            flushSync(() => {
              navigate(to);
            });
          });
        }}
      >
        {children}
      </a>
    );
  };