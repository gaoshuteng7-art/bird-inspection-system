export function stampToDateTime(timestamp) {
  const msStamp = timestamp.toString().length === 10 ? timestamp * 1000 : timestamp
  const date = new Date(msStamp)
  const padZero = (num) => String(num).padStart(2, '0')
  const year = date.getFullYear()
  const month = padZero(date.getMonth() + 1)
  const day = padZero(date.getDate())
  const hour = padZero(date.getHours())
  const minute = padZero(date.getMinutes())
  const second = padZero(date.getSeconds())
  return { date: `${year}-${month}-${day}`, time: `${hour}:${minute}:${second}`}
}