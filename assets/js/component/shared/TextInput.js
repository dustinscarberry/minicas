const TextInput = ({name, value, onChange, onBlur, readonly, autoFocus}) => {
  return <input
    type="text"
    className="form-control"
    name={name}
    autoFocus={autoFocus}
    onChange={onChange}
    onBlur={onBlur}
    value={value}
    readOnly={readonly}
  />
}

TextInput.defaultProps = {
  name: undefined,
  value: '',
  onChange: undefined,
  onBlur: undefined,
  readonly: false,
  autoFocus: undefined
}

export default TextInput;