import { useState, useEffect } from 'react';
import cloneDeep from 'lodash/cloneDeep';
import { isOk, formatTimestampToNumbericDateTime } from '../../../logic/utils';
import { fetchSessions, deleteSession } from './logic';
import Table from '../../shared/Table';
import Loader from '../../shared/Loader';
import SessionDetails from './SessionDetails';

const SessionViewer = (props) => {
  const [sessions, setSessions] = useState();
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    loadSessions();
  }, []);

  const loadSessions = async () => {
    const rsp = await fetchSessions();

    if (isOk(rsp)) {
      setSessions(rsp.data.data);
      setIsLoading(false);
    } 
  }

  const handleDeleteSession = async (id) => {
    if (confirm('Delete session?')) {
      const rsp = await deleteSession(id);

      if (isOk(rsp)) {
        const deletedSessionIndex = sessions.findIndex((session) => {
          return session.id == id;
        });

        const sessionsClone = cloneDeep(sessions);
        sessionsClone.splice(deletedSessionIndex, 1);
        setSessions(sessionsClone);
      }
    }
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
          },
          {
            value: <button
              className="icon-btn icon-btn-delete fa-solid fa-trash-can"
              onClick={() => handleDeleteSession(line.id)}
              title="Delete"
            ></button>
          }
        ],
        metaData: line
      }
    });
  
    return {
      headers: [
        {title: 'Session Details', width: '800px'},
        {title: 'Created'},
        {title: 'Expires'},
        {title: '', searchable: false, sortable: false}
      ],
      data: lines
    }
  }

  if (isLoading) return <Loader/>

  const tableData = convertSessionsToTable(sessions);

  return <div className="session-viewer">
    <div className="dashboard-subheader">
      <div className="container-fluid container-fixed-lg">
        <div className="row">
          <div className="col-lg-12">
            <div className="breadcrumb-container">
              <span className="breadcrumb">Dashboard</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div className="dashboard-panels">
      <div className="container-fluid container-fixed-lg">
        <div className="row">
          <div className="col-lg-12">
            <h2>Active Sessions ({ sessions.length })</h2>
            <Table
              headers={tableData.headers}
              data={tableData.data}
              searchable={true}
              sortable={true}
              noDataMessage="No current active user sessions"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
}

export default SessionViewer;