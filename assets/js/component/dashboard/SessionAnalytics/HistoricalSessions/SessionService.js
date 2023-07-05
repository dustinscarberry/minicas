import { useState } from 'react';
import classnames from 'classnames';
import { formatTimestampToNumbericDateTime } from '../../../../logic/utils';

const SessionService = ({service}) => {
  const [isOpen, setIsOpen] = useState(false);

  const toggleDetails = () => {
    setIsOpen(!isOpen);
  }

  return <div className="session-service">
    <div className="session-service-handle" onClick={toggleDetails}>
      <span>- { service.service.name }</span>
      <span>| { formatTimestampToNumbericDateTime(service.created) }</span>
      <i className={classnames('fas fa-caret-down toggle-caret', {'is-open': isOpen})}></i>
    </div>
    
    {isOpen &&
      <>
        <pre className="active-service-response">{service.replyTo}</pre>
        <pre className="active-service-response">{JSON.stringify(JSON.parse(service.attributes), null, 4)}</pre>
      </>
    }
    
  </div>
}

export default SessionService;