import { useState, useEffect } from 'react';
import { isOk, formatTimestampToNumbericDateTime } from '../../../../logic/utils';
import { fetchSessions, fetchServices } from '../logic';
import InlineLoader from '../../../shared/InlineLoader';
import SelectBox from '../../../shared/SelectBox';
import TextInput from '../../../shared/TextInput';
import Label from '../../../shared/Label';
import FormField from '../../../shared/FormField';
import Table from '../../../shared/Table';
import SessionDetails from './SessionDetails';

const HistoricalSessions = (props) => {
  const [sessions, setSessions] = useState();
  const [sessionsInterval, setSessionsInterval] = useState('1hour');
  const [service, setService] = useState();
  const [services, setServices] = useState();
  const [username, setUsername] = useState('');
  
  useEffect(() => {
    loadSessions();
  }, [sessionsInterval, service]);

  useEffect(() => {
    loadServices();
  }, []);

  const loadSessions = async () => {
    setSessions([]);
    const rsp = await fetchSessions(sessionsInterval, service, username);

    if (isOk(rsp))
      setSessions(rsp.data.data);
  }

  const loadServices = async () => {
    const rsp = await fetchServices();

    if (isOk(rsp))
      setServices(rsp.data.data);
  }

  const handleChangeInterval = (e) => {
    setSessionsInterval(e.target.value);
  }

  const handleChangeService = (e) => {
    setService(e.target.value);
  }

  const handleChangeUsername = (e) => {
    setUsername(e.target.value);
  }

  const convertSessionsToTable = (data) => {
    const lines = data.map(line => {
      return {
        rowData: [
          {
            value: <SessionDetails
              session={line}
            />,
            searchValue: line.user,
            sortValue: line.user
          },
          {
            value: line.created ? formatTimestampToNumbericDateTime(line.created) : '',
            sortValue: line.created
          },
          {
            value: line.expiration ? formatTimestampToNumbericDateTime(line.expiration) : '',
            sortValue: line.expiration
          }
        ],
        metaData: line
      }
    });
  
    return {
      headers: [
        {title: 'Session Details', width: '800px'},
        {title: 'Created'},
        {title: 'Expires'}
      ],
      data: lines
    }
  }

  if (!services) return <InlineLoader/>

  const tableData = convertSessionsToTable(sessions);

  return <div className="dashboard-panel-block">
    <div>
      <h3 className="dashboard-panel-header">Historical Sessions</h3>
      <FormField>
        <Label text="Time Interval"/>
        <SelectBox
          value={sessionsInterval}
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
      </FormField>
      <FormField>
        <Label text="Service"/>
        <SelectBox
          value={service}
          options={services.map(service => {
            return {key: service.id, value: service.name};
          })}
          onChange={handleChangeService}
          includeBlank={true}
        />
      </FormField>
      <FormField>
        <Label text="Username"/>
        <TextInput
          value={username}
          onChange={handleChangeUsername}
          onBlur={loadSessions}
        />
      </FormField>
    </div>

    {!sessions
      ? <InlineLoader/>
      : <Table
        headers={tableData.headers}
        data={tableData.data}
        searchable={true}
        sortable={true}
        noDataMessage="No user sessions found"
      />
    }
 
  </div>
}

export default HistoricalSessions;