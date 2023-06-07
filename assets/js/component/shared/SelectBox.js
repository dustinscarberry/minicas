const SelectBox = ({name, value, options, includeBlank, onChange}) => {
  const optionNodes = options.map((option, i) => {
    return <option key={i} value={option.key}>{option.value}</option>
  });

  if (includeBlank)
    optionNodes.unshift(<option key="-1" value=""></option>);

  return <div className="select-wrapper">
    <select className="form-control" name={name} onChange={onChange} value={value}>
      {optionNodes}
    </select>
  </div>
}

SelectBox.defaultProps = {
  name: undefined,
  value: '',
  options: [],
  inclueBlank: false,
  onChange: undefined
}

export default SelectBox;