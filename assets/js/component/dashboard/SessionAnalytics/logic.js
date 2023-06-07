import axios from 'axios';

export const fetchServiceAnalytics = async (timeInterval) => {
  return await axios.get('/api/v1/sessionanalytics/services?time_interval=' + timeInterval);
}

export const fetchOverallAnalytics = async (timeInterval) => {
  return await axios.get('/api/v1/sessionanalytics/overall?time_interval=' + timeInterval);
}