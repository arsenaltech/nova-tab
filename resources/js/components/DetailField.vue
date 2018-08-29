<template>
    <tabs >

        <tab :name="tab.name" v-for="tab in availableTabs" :key="tab.id" >
            <component
              :class="{'remove-bottom-border': index == tab.fields.length - 1}"
              :key="index"
              v-for="(field, index) in tab.fields"
              :is="resolveComponentName(field)"
              :resource-name="resourceName"
              :resource-id="resourceId"
              :resource="resource"
              :field="field"
              @actionExecuted="actionExecuted"
            />
        </tab>

    </tabs>
</template>

<script>

  export default {

    props: ['fields', 'field'],
    computed: {
      /**
       * Get the available field panels.
       */
      availableTabs() {
          var tabs = {}

          var fields = _.toArray(JSON.parse(JSON.stringify(this.field.fields)))

          fields.forEach(field => {
            if (tabs[field.tab]) {
            return tabs[field.tab].fields.push(field)
          }

          tabs[field.tab] = this.createTabForField(field)
        })

          return _.toArray(tabs)

      }},
    methods: {

      createTabForField(field) {
        return {fields: [field], name: field.tab, id: 'tab-'+field.tab}
      },
      resolveComponentName(field) {
        return field.prefixComponent ? 'detail-' + field.component : field.component
      },
    }
}
</script>
