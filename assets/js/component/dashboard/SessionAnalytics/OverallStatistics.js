import { useState, useEffect } from 'react';
import { isOk } from '../../../logic/utils';
import { fetchOverallAnalytics } from './logic';
import InlineLoader from '../../shared/InlineLoader';
import SelectBox from '../../shared/SelectBox';

const OverallStatistics = (props) => {
  const [overallStatistics, setOverallStatistics] = useState();
  const [timeInterval, setTimeInterval] = useState('1hour');

  useEffect(() => {
    loadOverallStatistics();
  }, [timeInterval]);

  const loadOverallStatistics = async () => {
    setOverallStatistics(undefined);
    const rsp = await fetchOverallAnalytics(timeInterval);

    if (isOk(rsp))
      setOverallStatistics(rsp.data.data);
  }

  const handleChangeInterval = (e) => {
    setTimeInterval(e.target.value);
  }

  return <div className="dashboard-panel-block">
    <h3 className="dashboard-panel-header">Overall Statistics</h3>
    <SelectBox
      value={timeInterval}
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
    <ul>

      {!overallStatistics
        ? <InlineLoader/>
        : <>
          <li>Total Sessions - {overallStatistics.totalSessions}</li>
          <li>Unique Users - {overallStatistics.uniqueUsers}</li>
        </>
      }
 
    </ul>
  </div>
}

export default OverallStatistics;