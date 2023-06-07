import { useState, useEffect } from 'react';
import { isOk } from '../../../logic/utils';
import { fetchServiceAnalytics } from './logic';
import Loader from '../../shared/Loader';
import SelectBox from '../../shared/SelectBox';

const AuthenticatedServices = (props) => {
  const [authenticatedServices, setAuthenticatedServices] = useState();
  const [authenticatedServicesInterval, setAuthenticatedServicesInterval] = useState('1hour');

  useEffect(() => {
    loadServiceAnalytics();
  }, [authenticatedServicesInterval]);

  const loadServiceAnalytics = async () => {
    const rsp = await fetchServiceAnalytics(authenticatedServicesInterval);

    if (isOk(rsp))
      setAuthenticatedServices(rsp.data.data);
  }

  const handleChangeInterval = (e) => {
    setAuthenticatedServicesInterval(e.target.value);
  }

  if (!authenticatedServices) return <Loader/>

  return <div className="dashboard-panel-block">
    <div>
      <h3 className="dashboard-panel-header">Authenticated Services</h3>
      <SelectBox
        value={authenticatedServicesInterval}
        options={[
          {key: '1hour', value: '1 Hour'},
          {key: '3hours', value: '3 Hours'},
          {key: '12hours', value: '12 Hours'},
          {key: '1day', value: '1 Day'},
          {key: '3days', value: '3 Days'},
          {key: '1week', value: '1 Week'}
        ]}
        onChange={handleChangeInterval}
      />
    </div>
    <ul>

      {authenticatedServices.map(service => {
        return <li key={service.name}>{service.name} - {service.sessions}</li>
      })}

    </ul>
  </div>
}

export default AuthenticatedServices;