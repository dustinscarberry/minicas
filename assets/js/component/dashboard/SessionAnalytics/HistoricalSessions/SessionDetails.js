import { useState } from 'react';
import { isOk } from '../../../../logic/utils';
import { fetchSession } from '../logic';
import SessionServices from './SessionServices';

const SessionDetails = ({session}) => {
  const [isOpen, setIsOpen] = useState(false);
  const [sessionDetails, setSessionDetails] = useState();

  const toggleDetails = () => {
    if (!sessionDetails)
      loadSession();

    setIsOpen(!isOpen);
  }

  const loadSession = async () => {
    const rsp = await fetchSession(session.id);
    
    if (isOk(rsp))
      setSessionDetails(rsp.data.data);
  }

  return <>
    <div className="session-details-handle" onClick={toggleDetails}>
      {session.user}
      <i className="fas fa-caret-down toggle-caret" style={{marginLeft: '.7rem'}}></i>
    </div>
    <SessionServices isOpen={isOpen} sessionDetails={sessionDetails}/>
  </>
}

export default SessionDetails;