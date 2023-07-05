import AuthenticatedServices from './AuthenticatedServices';
import OverallStatistics from './OverallStatistics';
import HistoricalSessions from './HistoricalSessions';

const SessionAnalytics = () => {









  return <div className="session-analytics">
    <div className="dashboard-subheader">



      <div className="container-fluid container-fixed-lg">
        <div className="row">
          <div className="col-lg-12">
            <div className="breadcrumb-container">
              <span className="breadcrumb">Session Analytics</span>
            </div>
          </div>
        </div>
      </div>



    </div>
    <div className="dashboard-panels">
      <div className="container-fluid container-fixed-lg">
        <div className="columns-2" style={{display: 'flex'}}>
          <AuthenticatedServices/>
          <OverallStatistics/>
        </div>
        <div className="row">
          <div className="col-lg-12">
            <HistoricalSessions/>
          </div>
        </div>
      </div>
    </div>
  </div>
}

export default SessionAnalytics;