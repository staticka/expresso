<script>
  const build =
  {
    accept: false,

    loading: false,

    error: null,

    publish()
    {
      const self = this

      self.loading = true

      const link = '<?php echo $link->set('/build') ?>'

      self.error = null

      axios.post(link)
        .then(function (response)
        {
          setTimeout(function ()
          {
            window.location.reload()
          }, 800)
        })
        .catch(function (error)
        {
          self.loading = false

          self.error = error.response.data
        })
    },
  }
</script>
