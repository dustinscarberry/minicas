import axios from 'axios';

export const fetchSessions = async () => {
  return await axios.get('/api/v1/sessions');
}

export const fetchSession = async (id) => {
  return await axios.get('/api/v1/sessions/' + id);
}

export const deleteSession = async (id) => {
  return await axios.delete('/api/v1/sessions/' + id);
}