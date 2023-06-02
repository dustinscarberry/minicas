import SessionService from './SessionService';
import Loader from '../../shared/Loader';

const SessionServices = ({isOpen, sessionDetails}) => {
  if (!isOpen) return null;

  if (!sessionDetails) return <Loader/>

  return <div className="session-services">
    {sessionDetails.authenticatedServices.map((service, i) => {
      return <SessionService key={i} service={service}/>
    })}
  </div>
}

export default SessionServices;