import SessionService from './SessionService';
import InlineLoader from '../../shared/InlineLoader';

const SessionServices = ({isOpen, sessionDetails}) => {
  if (!isOpen) return null;

  if (!sessionDetails) return <InlineLoader/>

  return <div className="session-services">
    {sessionDetails.authenticatedServices.map((service, i) => {
      return <SessionService key={i} service={service}/>
    })}
  </div>
}

export default SessionServices;