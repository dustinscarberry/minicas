const Label = ({text, helpInfo}) => {
  return <label>
    {text}
    {helpInfo &&
      <i className="form-label-help fas fa-question-circle"></i>
    }
  </label>
}

Label.defaultProps = {
  text: undefined,
  helpInfo: undefined
}

export default Label;