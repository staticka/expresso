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

      const link = '<?= $link->set('/build') ?>'

      self.error = null

      axios.post(link)
        .then(function (response)
        {
          window.location.reload()
        })
        .catch(function (error)
        {
          self.error = error.response.data
        })
        .finally(function ()
        {
          self.loading = false
        })
    },
  }
</script>