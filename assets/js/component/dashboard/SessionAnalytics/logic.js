import axios from 'axios';

export const fetchServiceAnalytics = async (timeInterval) => {
  return await axios.get('/api/v1/sessionanalytics/services?time_interval=' + timeInterval);
}

export const fetchOverallAnalytics = async (timeInterval) => {
  return await axios.get('/api/v1/sessionanalytics/overall?time_interval=' + timeInterval);
}

export const fetchSessions = async (timeInterval, service, user) => {
  return await axios.get('/api/v1/sessions?expired=true&time_interval=' + timeInterval + '&service=' + service + '&user=' + user);
}

export const fetchSession = async (id) => {
  return await axios.get('/api/v1/sessions/' + id);
}

export const fetchServices = async () => {
  return await axios.get('/api/v1/serviceproviders');
}