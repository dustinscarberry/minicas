import { DateTime } from 'luxon';

export const isOk = rsp => {
  return (rsp && rsp.status == 200 && !rsp.data.error);
}

export const isError = rsp => {
  return (rsp && rsp.status == 200 && rsp.data.hasOwnProperty('error'));
}

export const formatTimestampToShortDate = (timestamp) => {
  return DateTime.fromSeconds(timestamp).toFormat('DD');
}

export const formatTimestampToShortDateTime = (timestamp) => {
  return DateTime.fromSeconds(timestamp).toFormat('ff');
}

export const formatTimestampToNumbericDateTime = (timestamp) => {
  return DateTime.fromSeconds(timestamp).toFormat('f');
}