import { useState, useEffect } from 'react';
import { isOk } from '../../../logic/utils';
import { fetchOverallAnalytics } from './logic';
import Loader from '../../shared/Loader';
import SelectBox from '../../shared/SelectBox';

const OverallStatistics = (props) => {
  const [overallStatistics, setOverallStatistics] = useState();
  const [timeInterval, setTimeInterval] = useState('1day');

  useEffect(() => {
    loadOverallStatistics();
  }, [timeInterval]);

  const loadOverallStatistics = async () => {
    const rsp = await fetchOverallAnalytics(timeInterval);

    if (isOk(rsp))
      setOverallStatistics(rsp.data.data);
  }

  const handleChangeInterval = (e) => {
    setTimeInterval(e.target.value);
  }

  //if (!overallStatistics) return <Loader/>

  return <div className="dashboard-panel-block">
    <h3 className="dashboard-panel-header">Overall Statistics</h3>
    <SelectBox
      value={timeInterval}
      options={[
        {key: '1hour', value: '1 Hour'},
        {key: '3hours', value: '3 Hours'},
        {key: '12hours', value: '12 Hours'},
        {key: '1day', value: '1 Day'}
      ]}
      onChange={handleChangeInterval}
    />
    <ul>
      <li>Total Sessions - xxxx</li>
      <li>Unique Users - xxxx</li>
    </ul>
  </div>
}

export default OverallStatistics;